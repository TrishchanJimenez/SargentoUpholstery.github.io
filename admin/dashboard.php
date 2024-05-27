<?php
require_once '../database_connection.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['access_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$sql = "
    SELECT
        (SELECT
            SUM(quoted_price)
        FROM
            orders
        JOIN
            order_date USING(order_id)
        JOIN
            quotes USING(quote_id)    
        WHERE
            order_status <> 'received'
            AND is_cancelled = 0
            AND WEEK(placement_date) = WEEK(CURDATE()) 
            AND YEAR(placement_date) = YEAR(CURDATE())) AS total_revenue_current_week,
        (SELECT
            SUM(quoted_price)
        FROM
            orders
        JOIN
            order_date USING(order_id)
        JOIN
            quotes USING(quote_id)    
        WHERE
            order_status <> 'received'
            AND is_cancelled = 0
            AND YEARWEEK(placement_date, 1) = YEARWEEK(CURDATE(), 1) - 1) AS total_revenue_last_week,
        (SELECT 
            COUNT(*) 
        FROM 
            orders
        JOIN 
            order_date USING(order_id)
        WHERE 
            WEEK(placement_date) = WEEK(CURDATE()) 
            AND YEAR(placement_date) = YEAR(CURDATE())) AS new_orders_current_week,
        (SELECT 
            COUNT(*) 
        FROM 
            orders 
        JOIN 
            order_date USING(order_id)
        WHERE 
            YEARWEEK(placement_date, 1) = YEARWEEK(CURDATE(), 1) - 1) AS new_orders_last_week,
        (SELECT
            COUNT(*)
        FROM
            orders
        WHERE 
            is_cancelled = 1
            AND WEEK(last_updated) = WEEK(CURDATE()) 
            AND YEAR(last_updated) = YEAR(CURDATE())) AS cancelled_orders_current_week,
        (SELECT
            COUNT(*)
        FROM
            orders
        WHERE 
            is_cancelled = 1
            AND YEARWEEK(last_updated, 1) = YEARWEEK(CURDATE(), 1) - 1) AS cancelled_orders_last_week,
        (SELECT
            COUNT(*)
        FROM
            orders
        JOIN (
            SELECT U.user_id,
            MIN(placement_date) AS first_order
            FROM orders O
            JOIN order_date OD USING(order_id)
            JOIN users U ON O.user_id = U.user_id
            WHERE user_type = 'customer'
            GROUP BY U.user_id
        ) AS first_orders ON orders.user_id = first_orders.user_id
        WHERE 
            WEEK(first_order) = WEEK(CURDATE()) 
            AND YEAR(first_order) = YEAR(CURDATE())) AS new_customers_current_week,
        (SELECT
            COUNT(*)
        FROM
            orders
        JOIN (
            SELECT U.user_id,
            MIN(placement_date) AS first_order
            FROM orders O
            JOIN order_date OD USING(order_id)
            JOIN users U ON O.user_id = U.user_id
            WHERE user_type = 'customer'
            GROUP BY U.user_id
        ) AS first_orders ON orders.user_id = first_orders.user_id
        WHERE 
            YEARWEEK(first_order, 1) = YEARWEEK(CURDATE(), 1) - 1) AS new_customers_last_week,
        (SELECT
            COUNT(*)
        FROM
            orders
        WHERE
            order_status = 'received'
            AND WEEK(last_updated) = WEEK(CURDATE())) AS completed_orders_current_week, 
        (SELECT
            COUNT(*)
        FROM
            orders
        WHERE
            order_status = 'received'
            AND YEARWEEK(last_updated, 1) = YEARWEEK(CURDATE(), 1) - 1) AS completed_orders_last_week 
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

$total_revenue_current_week = $stats['total_revenue_current_week'];
if($stats['total_revenue_last_week'] == 0) {
    $total_revenue_stat_change = 100;
} else {
    $total_revenue_stat_change = round(($total_revenue_current_week - $stats['total_revenue_last_week']) / $stats['total_revenue_last_week'] * 100, 2);
}

$new_orders_current_week = $stats['new_orders_current_week'];
if($stats['new_orders_last_week'] == 0) {
    $new_order_stat_change = 100;
} else {
    $new_order_stat_change = round(($new_orders_current_week - $stats['new_orders_last_week']) / $stats['new_orders_last_week'] * 100, 2);
}

$cancelled_orders_current_week = $stats['cancelled_orders_current_week'];
if($stats['cancelled_orders_last_week'] == 0) {
    $cancelled_orders_stat_change = 100;
} else {
    $cancelled_orders_stat_change = round(($cancelled_orders_current_week - $stats['cancelled_orders_last_week']) / $stats['cancelled_orders_last_week'] * 100, 2);
}

$new_customers_current_week = $stats['new_customers_current_week'];
if($stats['new_customers_last_week'] == 0) {
    $new_customers_stat_change = 100;
} else {
    $new_customers_stat_change = round(($new_customers_current_week - $stats['new_customers_last_week']) / $stats['new_customers_last_week'] * 100, 2);
}

$completed_orders_current_week = $stats['completed_orders_current_week'];
if($stats['completed_orders_last_week'] == 0) {
    $completed_orders_stat_change = 100;
} else {
    $completed_orders_stat_change = round(($completed_orders_current_week - $stats['completed_orders_last_week']) / $stats['completed_orders_last_week'] * 100, 2);
}

$avg_rating_sql = "
    SELECT AVG(rating) AS average_rating FROM reviews
";

$stmt = $conn->prepare($avg_rating_sql);
$stmt->execute();
$avg_rating = round($stmt->fetch(PDO::FETCH_ASSOC)['average_rating'], 2);

$review_count = "
    SELECT
        COUNT(*) AS review_count
    FROM
        reviews";
$stmt = $conn->prepare($review_count);
$stmt->execute();
$review_count = $stmt->fetch(PDO::FETCH_ASSOC)['review_count'];

$ratings_by_type = "
    SELECT
        service_type AS order_type,
        AVG(rating) AS average_rating
    FROM
        reviews
    JOIN orders USING (order_id)
    JOIN quotes USING (quote_id)
    GROUP BY order_type
";

$stmt = $conn->prepare($ratings_by_type);
$stmt->execute();
$ratings_by_type = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin/orders.css">
    <link rel="stylesheet" href="../css/dashboard_test.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="dashboard">
        <?php require 'sidebar.php' ?>
        <div class="dashboard-content">
            <p class="main-title">Dashboard</p>
            <hr class="divider">
            <div class="top-charts">
                <div class="orders-summary stat-container">
                    <p class="orders-title">Business Statistics</p>
                    <p class="summary-title">Orders Summary</p>
                    <div class="stat-categories">
                        <div class="stat-category category-volume">
                            <p class="category-title">Order Volume</p>
                            <p class="category-value">₱<?= $total_revenue_current_week ?></p> 
                            <p class="rise-value"><span class="<?= $total_revenue_stat_change >= 0 ? 'stat-up' : 'stat-down' ?>"><?= $total_revenue_stat_change ?>%</span> from last week</p> 
                        </div>
                        <div class="stat-category category-new">
                            <p class="category-title">New Orders</p>
                            <p class="category-value"><?= $new_orders_current_week ?></p> 
                            <p class="rise-value"><span class="<?= $new_order_stat_change >= 0 ? 'stat-up' : 'stat-down' ?>"><?= $new_order_stat_change ?>%</span> from last week</p> 
                        </div>
                        <div class="stat-category category-cancelled">
                            <p class="category-title">Cancelled Orders</p>
                            <p class="category-value"><?= $cancelled_orders_current_week ?></p> 
                            <p class="rise-value"><span class="<?= $cancelled_orders_stat_change >= 0 ? 'stat-up' : 'stat-down' ?>"><?= $cancelled_orders_stat_change ?>%</span> from last week</p> 
                        </div>
                        <div class="stat-category category-customers">
                            <p class="category-title">Completed Orders</p>
                            <p class="category-value"><?= $completed_orders_current_week ?></p> 
                            <p class="rise-value"><span class="<?= $completed_orders_stat_change >= 0 ? 'stat-up' : 'stat-down' ?>"><?= $completed_orders_stat_change ?>%</span> from last week</p> 
                        </div>
                        <div class="stat-category category-current">
                            <p class="category-title">New Customers</p>
                            <p class="category-value"><?= $new_customers_current_week ?></p> 
                            <p class="rise-value"><span class="<?= $new_customers_stat_change >= 0 ? 'stat-up' : 'stat-down' ?>"><?= $new_customers_stat_change ?>%</span> from last week</p> 
                        </div>
                    </div>
                </div>
                <div class="review-stats stat-container">
                    <p class="orders-title">Average Rating</p>
                    <div class="average-rating">
                        <span class="rating-text"> <?= $avg_rating ?> </span>
                        <span class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <span class="fa fa-star
                                    <?= $i <= $avg_rating ? 'checked' : '' ?>">
                                </span>
                            <?php } ?>
                        </span>
                    </div>
                    <p class="orders-title by-type">By Type</p>
                    <p class="sub-category">MTO</p>
                    <div class="average-rating">
                        <span class="rating-text"> <?= round($ratings_by_type[0]['average_rating'], 2) ?> </span>
                        <span class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <span class="fa fa-star
                                    <?= $i <= $ratings_by_type[0]['average_rating'] ? 'checked' : '' ?>">
                                </span>
                            <?php } ?>
                        </span>
                    </div>
                    <?php if(isset($ratings[1]['average_rating'])) : ?>
                        <p class="sub-category">Repair</p>
                        <div class="average-rating">
                            <span class="rating-text"> <?= round($ratings_by_type[1]['average_rating'],2) ?> </span>
                            <span class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <span class="fa fa-star
                                        <?= $i <= $ratings_by_type[1]['average_rating'] ? 'checked' : '' ?>">
                                    </span>
                                <?php } ?>
                            </span>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="bottom-charts">
                <div class="order-status stat-container">
                    <p class="chart-title">Currrent Orders Status</p>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>  
                <div class="order-type stat-container">
                    <p class="chart-title">Current Orders Type</p>
                    <div class="chart-container">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
                <div class="furniture-categories stat-container">
                    <p class="chart-title">Orders by Furniture</p>
                    <div class="chart-container">
                        <canvas id="furnitureChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/dashboard_test.js"></script>
</body>
</html>