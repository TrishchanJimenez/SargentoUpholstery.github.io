<?php
require '../database_connection.php';
session_start();

// Fetch form inputs
$search_type = $_GET['search-order'] ?? '';
$search_input = $_GET['search-input'] ?? '';
$service_type = $_GET['service-type'] ?? '';
$order_prod_status = $_GET['order-prod-status'] ?? '';
$order_payment_status = $_GET['order-payment-status'] ?? '';
$order_sort = $_GET['order-sort'] ?? '';
$start_date = $_GET['start-date'] ?? '';
$end_date = $_GET['end-date'] ?? '';
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$date_range = $_GET['date-range'] ?? '';

// Base SQL queries
$count_query = "
    SELECT 
        COUNT(*) AS total_records
    FROM 
        orders O 
    JOIN 
        quotes Q USING(quote_id)
    JOIN 
        users U ON Q.customer_id = U.user_id
    WHERE 1
";

$query = "
    SELECT
        O.order_id,
        U.name AS customer_name,
        Q.service_type AS order_type,
        Q.total_price AS price,
        GROUP_CONCAT(CONCAT(UPPER(SUBSTRING(I.furniture, 1, 1)), LOWER(SUBSTRING(I.furniture, 2))) SEPARATOR ', ') AS item,
        O.created_at AS placement_date,
        O.order_phase AS prod_status,
        O.payment_phase AS payment_status
    FROM 
        orders O 
    JOIN 
        quotes Q USING (quote_id)
    LEFT JOIN
        items I USING (quote_id)
    JOIN 
        users U ON Q.customer_id = U.user_id
    WHERE 1
";

// Apply search filters
if (!empty($search_input)) {
    switch($search_type) {
        case "order_id":
            if (is_numeric($search_input)) {
                $count_query .= " AND O.order_id = $search_input";
                $query .= " AND O.order_id = $search_input";
            }
            break;
        case "item":
            $count_query .= " AND Q.furniture_type LIKE '%$search_input%'";
            $query .= " AND Q.furniture_type LIKE '%$search_input%'";
            break;
        case "customer_name":
            $count_query .= " AND U.name LIKE '%$search_input%'";
            $query .= " AND U.name LIKE '%$search_input%'";
            break;
    }
}

// Apply service type filter
if (!($service_type === 'default' || empty($service_type))) {
    $count_query .= " AND Q.service_type = '$service_type'";
    $query .= " AND Q.service_type = '$service_type'";
}

// Apply production status filter
if (!($order_prod_status === 'default' || empty($order_prod_status))) {
    $count_query .= " AND O.order_phase = '$order_prod_status'";
    $query .= " AND O.order_phase = '$order_prod_status'";
}

// Apply payment status filter
if (!($order_payment_status === 'default' || empty($order_payment_status))) {
    $count_query .= " AND O.payment_phase = '$order_payment_status'";
    $query .= " AND O.payment_phase = '$order_payment_status'";
}

// Apply date range filter based on selected option
if ($date_range === 'today') {
    $today = date('Y-m-d');
    $count_query .= " AND DATE(O.created_at) = '$today'";
    $query .= " AND DATE(O.created_at) = '$today'";
} elseif ($date_range === 'this-week') {
    $start_week = date('Y-m-d', strtotime('monday this week'));
    $end_week = date('Y-m-d', strtotime('sunday this week'));
    $count_query .= " AND DATE(O.created_at) BETWEEN '$start_week' AND '$end_week'";
    $query .= " AND DATE(O.created_at) BETWEEN '$start_week' AND '$end_week'";
} elseif ($date_range === 'this-month') {
    $start_month = date('Y-m-01');
    $end_month = date('Y-m-t');
    $count_query .= " AND DATE(O.created_at) BETWEEN '$start_month' AND '$end_month'";
    $query .= " AND DATE(O.created_at) BETWEEN '$start_month' AND '$end_month'";
}

// Apply custom date range if provided
if (!empty($start_date) && !empty($end_date)) {
    $count_query .= " AND O.created_at BETWEEN '$start_date' AND '$end_date'";
    $query .= " AND O.created_at BETWEEN '$start_date' AND '$end_date'";
}

// Execute count query to get total records
$count_result = $conn->query($count_query);
$total_records = $count_result->fetch(PDO::FETCH_ASSOC)['total_records'];

// Group by order ID for consolidated results
$query .= " GROUP BY O.order_id";

// Apply sorting and pagination
if (!($order_sort === 'default' || empty($order_sort))) {
    $query .= " ORDER BY $order_sort DESC";
} else {
    $query .= " ORDER BY O.updated_at DESC";
}

$query .= " LIMIT 10";
if ($current_page !== 1) {
    $query .= " OFFSET " . (($current_page - 1) * 10);
}

// Execute main query to fetch filtered orders
$stmt = $conn->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin/orders.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/report_generated.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .content-container,
            .content-container * {
                visibility: visible;
            }

            .content-container {
                position: absolute;
                left: 0;
                top: 0;
                width: ;
                height: 100%;
            }

            .selector-container {
                padding: 0.4rem 0.3rem
            }

            .filter {
                display: none;

            }

            .Print_button {
                display: none;
            }

            .Download_button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="orders">
        <div class="admin-sidebar">
            <h1>
                <p class="text-gold">Sargento</p>
                <p class="text-gold">Upholstery</p>
            </h1>
            <ul class="admin-nav">
                <a href="./dashboard.php" class="admin-link fill-icon" data-page="dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                    <svg class="hovered" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="./orders.php" class="admin-link" data-page="orders">
                    <svg class="unhovered" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="shopping-bag-02">
                            <path id="Icon" d="M16.0004 9V6C16.0004 3.79086 14.2095 2 12.0004 2C9.79123 2 8.00037 3.79086 8.00037 6V9M3.59237 10.352L2.99237 16.752C2.82178 18.5717 2.73648 19.4815 3.03842 20.1843C3.30367 20.8016 3.76849 21.3121 4.35839 21.6338C5.0299 22 5.94374 22 7.77142 22H16.2293C18.057 22 18.9708 22 19.6423 21.6338C20.2322 21.3121 20.6971 20.8016 20.9623 20.1843C21.2643 19.4815 21.179 18.5717 21.0084 16.752L20.4084 10.352C20.2643 8.81535 20.1923 8.04704 19.8467 7.46616C19.5424 6.95458 19.0927 6.54511 18.555 6.28984C17.9444 6 17.1727 6 15.6293 6L8.37142 6C6.82806 6 6.05638 6 5.44579 6.28984C4.90803 6.54511 4.45838 6.95458 4.15403 7.46616C3.80846 8.04704 3.73643 8.81534 3.59237 10.352Z" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                    </svg>
                    <span>Orders</span>
                </a>
                <a href="./quotations.php" class="admin-link fill-icon" data-page="quotations">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4">
                        <path d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111">
                        <path d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z" />
                    </svg>
                    <span>Quotes</span>
                </a>
                <a href="./cms.php" class="admin-link fill-icon" data-page="cms">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="edit_document_24dp_FILL0_wght400_GRAD0_opsz24 1">
                            <path id="Vector" d="M14 22V18.925L19.525 13.425C19.675 13.275 19.8417 13.1667 20.025 13.1C20.2083 13.0333 20.3917 13 20.575 13C20.775 13 20.9667 13.0375 21.15 13.1125C21.3333 13.1875 21.5 13.3 21.65 13.45L22.575 14.375C22.7083 14.525 22.8125 14.6917 22.8875 14.875C22.9625 15.0583 23 15.2417 23 15.425C23 15.6083 22.9667 15.7958 22.9 15.9875C22.8333 16.1792 22.725 16.35 22.575 16.5L17.075 22H14ZM15.5 20.5H16.45L19.475 17.45L19.025 16.975L18.55 16.525L15.5 19.55V20.5ZM6 22C5.45 22 4.97917 21.8042 4.5875 21.4125C4.19583 21.0208 4 20.55 4 20V4C4 3.45 4.19583 2.97917 4.5875 2.5875C4.97917 2.19583 5.45 2 6 2H14L20 8V11H18V9H13V4H6V20H12V22H6ZM19.025 16.975L18.55 16.525L19.475 17.45L19.025 16.975Z" fill="#C4CAD4" />
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111">
                        <path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z" />
                    </svg>
                    <span>Content</span>
                </a>
                <a href="./chat.php" class="admin-link fill-icon" id="chatSystemLink" data-page="chat">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4">
                        <path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111">
                        <path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                    </svg>
                    <span>Chat</span>
                </a>
                <a href="./reports.php" class="admin-link fill-icon" data-page="reports">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4">
                        <path d="M280-280h80v-200h-80v200Zm320 0h80v-400h-80v400Zm-160 0h80v-120h-80v120Zm0-200h80v-80h-80v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111">
                        <path d="M280-280h80v-200h-80v200Zm320 0h80v-400h-80v400Zm-160 0h80v-120h-80v120Zm0-200h80v-80h-80v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                    </svg>
                    <span>Reports</span>
                </a>
            </ul>
            <ul class="admin-extra">
                <a href="../index.php" class="admin-link fill-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="home_24dp_FILL0_wght400_GRAD0_opsz24 1">
                            <path id="Vector" d="M6 19H9V13H15V19H18V10L12 5.5L6 10V19ZM4 21V9L12 3L20 9V21H13V15H11V21H4Z" fill="#C4CAD4" />
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111">
                        <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                    </svg>
                    <span>Back To Home</span>
                </a>
                <a href="../api/logout.php" class="admin-link">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="Icons">
                            <path id="Icon" d="M11.1956 2.87988C16.4976 2.87988 20.7956 7.17795 20.7956 12.4799C20.7956 17.7818 16.4976 22.0799 11.1956 22.0799M8.79557 16.3199L4.95557 12.4799M4.95557 12.4799L8.79557 8.63988M4.95557 12.4799H16.9556" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                    </svg>
                    <span>Logout</span>
                </a>
            </ul>
        </div>

        <div class="content-container">
            <p class="main-title">Summarized Statistics</p>
            <hr class="divider1">
            <div class="tabs_dropdown_container">
                <select id="tab-dropdown" class="dropdown">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="stats_container">
                <?php
        // SQL queries for daily, weekly, monthly, and yearly statistics
        $sql = "
            SELECT
                (SELECT SUM(total_price) FROM orders O JOIN quotes Q USING(quote_id) WHERE order_phase = 'received' AND order_phase <> 'cancelled' AND DATE(O.created_at) = CURDATE()) AS total_revenue_today,
                (SELECT SUM(total_price) FROM orders O JOIN quotes Q USING(quote_id) WHERE order_phase = 'received' AND order_phase <> 'cancelled' AND WEEK(O.created_at) = WEEK(CURDATE()) AND YEAR(O.created_at) = YEAR(CURDATE())) AS total_revenue_current_week,
                (SELECT SUM(total_price) FROM orders O JOIN quotes Q USING(quote_id) WHERE order_phase = 'received' AND order_phase <> 'cancelled' AND MONTH(O.created_at) = MONTH(CURDATE()) AND YEAR(O.created_at) = YEAR(CURDATE())) AS total_revenue_current_month,
                (SELECT SUM(total_price) FROM orders O JOIN quotes Q USING(quote_id) WHERE order_phase = 'received' AND order_phase <> 'cancelled' AND YEAR(O.created_at) = YEAR(CURDATE())) AS total_revenue_current_year,

                (SELECT COUNT(*) FROM orders O WHERE DATE(O.created_at) = CURDATE()) AS new_orders_today,
                (SELECT COUNT(*) FROM orders O WHERE WEEK(O.created_at) = WEEK(CURDATE()) AND YEAR(O.created_at) = YEAR(CURDATE())) AS new_orders_current_week,
                (SELECT COUNT(*) FROM orders O WHERE MONTH(O.created_at) = MONTH(CURDATE()) AND YEAR(O.created_at) = YEAR(CURDATE())) AS new_orders_current_month,
                (SELECT COUNT(*) FROM orders O WHERE YEAR(O.created_at) = YEAR(CURDATE())) AS new_orders_current_year,

                (SELECT COUNT(*) FROM orders WHERE order_phase = 'cancelled' AND DATE(updated_at) = CURDATE()) AS cancelled_orders_today,
                (SELECT COUNT(*) FROM orders O WHERE order_phase = 'cancelled' AND WEEK(updated_at) = WEEK(CURDATE()) AND YEAR(updated_at) = YEAR(CURDATE())) AS cancelled_orders_current_week,
                (SELECT COUNT(*) FROM orders O WHERE order_phase = 'cancelled' AND MONTH(updated_at) = MONTH(CURDATE()) AND YEAR(updated_at) = YEAR(CURDATE())) AS cancelled_orders_current_month,
                (SELECT COUNT(*) FROM orders O WHERE order_phase = 'cancelled' AND YEAR(updated_at) = YEAR(CURDATE())) AS cancelled_orders_current_year,

                (SELECT COUNT(*) FROM orders JOIN quotes Q USING(quote_id) JOIN (SELECT Q.customer_id AS user_id, MIN(O.created_at) AS first_order FROM orders O JOIN quotes Q USING(quote_id) JOIN users U ON Q.customer_id = U.user_id WHERE user_type = 'customer' GROUP BY user_id) AS first_orders ON Q.customer_id = first_orders.user_id WHERE DATE(first_order) = CURDATE()) AS new_customers_today,
                (SELECT COUNT(*) FROM orders JOIN quotes Q USING(quote_id) JOIN (SELECT Q.customer_id AS user_id, MIN(O.created_at) AS first_order FROM orders O JOIN quotes Q USING(quote_id) JOIN users U ON Q.customer_id = U.user_id WHERE user_type = 'customer' GROUP BY user_id) AS first_orders ON Q.customer_id = first_orders.user_id WHERE WEEK(first_order) = WEEK(CURDATE()) AND YEAR(first_order) = YEAR(CURDATE())) AS new_customers_current_week,
                (SELECT COUNT(*) FROM orders JOIN quotes Q USING(quote_id) JOIN (SELECT Q.customer_id AS user_id, MIN(O.created_at) AS first_order FROM orders O JOIN quotes Q USING(quote_id) JOIN users U ON Q.customer_id = U.user_id WHERE user_type = 'customer' GROUP BY user_id) AS first_orders ON Q.customer_id = first_orders.user_id WHERE MONTH(first_order) = MONTH(CURDATE()) AND YEAR(first_order) = YEAR(CURDATE())) AS new_customers_current_month,
                (SELECT COUNT(*) FROM orders JOIN quotes Q USING(quote_id) JOIN (SELECT Q.customer_id AS user_id, MIN(O.created_at) AS first_order FROM orders O JOIN quotes Q USING(quote_id) JOIN users U ON Q.customer_id = U.user_id WHERE user_type = 'customer' GROUP BY user_id) AS first_orders ON Q.customer_id = first_orders.user_id WHERE YEAR(first_order) = YEAR(CURDATE())) AS new_customers_current_year,

                (SELECT COUNT(*) FROM orders WHERE order_phase = 'received' AND DATE(updated_at) = CURDATE()) AS completed_orders_today,
                (SELECT COUNT(*) FROM orders WHERE order_phase = 'received' AND WEEK(updated_at) = WEEK(CURDATE())) AS completed_orders_current_week,
                (SELECT COUNT(*) FROM orders WHERE order_phase = 'received' AND MONTH(updated_at) = MONTH(CURDATE()) AND YEAR(updated_at) = YEAR(CURDATE())) AS completed_orders_current_month,
                (SELECT COUNT(*) FROM orders WHERE order_phase = 'received' AND YEAR(updated_at) = YEAR(CURDATE())) AS completed_orders_current_year
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_revenue_today = number_format($stats['total_revenue_today']);
        $total_revenue_current_week = number_format($stats['total_revenue_current_week']);
        $total_revenue_current_month = number_format($stats['total_revenue_current_month']);
        $total_revenue_current_year = number_format($stats['total_revenue_current_year']);

        $new_orders_today = $stats['new_orders_today'];
        $new_orders_current_week = $stats['new_orders_current_week'];
        $new_orders_current_month = $stats['new_orders_current_month'];
        $new_orders_current_year = $stats['new_orders_current_year'];

        $cancelled_orders_today = $stats['cancelled_orders_today'];
        $cancelled_orders_current_week = $stats['cancelled_orders_current_week'];
        $cancelled_orders_current_month = $stats['cancelled_orders_current_month'];
        $cancelled_orders_current_year = $stats['cancelled_orders_current_year'];

        $new_customers_today = $stats['new_customers_today'];
        $new_customers_current_week = $stats['new_customers_current_week'];
        $new_customers_current_month = $stats['new_customers_current_month'];
        $new_customers_current_year = $stats['new_customers_current_year'];

        $completed_orders_today = $stats['completed_orders_today'];
        $completed_orders_current_week = $stats['completed_orders_current_week'];
        $completed_orders_current_month = $stats['completed_orders_current_month'];
        $completed_orders_current_year = $stats['completed_orders_current_year'];

        $avg_rating_sql = "
            SELECT AVG(rating) AS average_rating FROM reviews
        ";

        $stmt = $conn->prepare($avg_rating_sql);
        $stmt->execute();
        $avg_rating = round($stmt->fetch(PDO::FETCH_ASSOC)['average_rating'], 2);

        $review_count_sql = "
            SELECT COUNT(*) AS review_count FROM reviews
        ";
        $stmt = $conn->prepare($review_count_sql);
        $stmt->execute();
        $review_count = $stmt->fetch(PDO::FETCH_ASSOC)['review_count'];

        $ratings_by_type_sql = "
            SELECT
            DATE(quotes.created_at) AS day,
            ROUND(AVG(CASE WHEN service_type = 'mto' THEN rating ELSE NULL END), 2) AS mto_daily_average_rating,
            ROUND(AVG(CASE WHEN service_type = 'repair' THEN rating ELSE NULL END), 2) AS repair_daily_average_rating,
            DATE_FORMAT(quotes.created_at, '%x-%v') AS week,
            ROUND(AVG(CASE WHEN service_type = 'mto' THEN rating ELSE NULL END), 2) AS mto_weekly_average_rating,
            ROUND(AVG(CASE WHEN service_type = 'repair' THEN rating ELSE NULL END), 2) AS repair_weekly_average_rating,
            DATE_FORMAT(quotes.created_at, '%Y-%m') AS month,
            ROUND(AVG(CASE WHEN service_type = 'mto' THEN rating ELSE NULL END), 2) AS mto_monthly_average_rating,
            ROUND(AVG(CASE WHEN service_type = 'repair' THEN rating ELSE NULL END), 2) AS repair_monthly_average_rating,
            YEAR(quotes.created_at) AS year,
            ROUND(AVG(CASE WHEN service_type = 'mto' THEN rating ELSE NULL END), 2) AS mto_yearly_average_rating,
            ROUND(AVG(CASE WHEN service_type = 'repair' THEN rating ELSE NULL END), 2) AS repair_yearly_average_rating
        FROM reviews
        JOIN orders USING (order_id)
        JOIN quotes ON orders.quote_id = quotes.quote_id
        GROUP BY day, week, month, year
        ORDER BY day, week, month, year;    
        ";

        $stmt = $conn->prepare($ratings_by_type_sql);
        $stmt->execute();
        $ratings_by_type = $stmt->fetch(PDO::FETCH_ASSOC);


        $mto_daily_average_rating = $ratings_by_type['mto_daily_average_rating'];
        $repair_daily_average_rating = $ratings_by_type['repair_daily_average_rating'];
        $mto_weekly_average_rating = $ratings_by_type['mto_weekly_average_rating'];
        $repair_weekly_average_rating = $ratings_by_type['repair_weekly_average_rating'];
        $mto_monthly_average_rating = $ratings_by_type['mto_monthly_average_rating'];
        $repair_monthly_average_rating = $ratings_by_type['repair_monthly_average_rating'];
        $mto_yearly_average_rating = $ratings_by_type['mto_yearly_average_rating'];
        $repair_yearly_average_rating = $ratings_by_type['repair_yearly_average_rating'];

        //For the breakdown of furnitures and  total sales
        $daily_sql = "
        SELECT
        'Daily' AS period,
        DATE(q.created_at) AS time_period,
        i.furniture AS furniture_type,
        COUNT(*) AS number_ordered,
        ROUND(SUM(i.item_price), 2) AS total_price_ordered
        FROM items i
        JOIN quotes q ON i.quote_id = q.quote_id
        JOIN orders o ON q.quote_id = o.quote_id
        WHERE o.order_phase = 'received'
        AND DATE(q.created_at) = CURDATE()  -- Filter for current day
        GROUP BY period, time_period, furniture_type;
    ";
    
    $weekly_sql = "
        SELECT
            DATE_FORMAT(q.created_at, '%x-%v') AS period,
            i.furniture AS furniture_type,
            COUNT(*) AS number_ordered,
            ROUND(SUM(i.item_price), 2) AS total_price_ordered
        FROM items i
        JOIN quotes q ON i.quote_id = q.quote_id
        JOIN orders o ON q.quote_id = o.quote_id
        WHERE o.order_phase = 'received'
        GROUP BY period, furniture_type
        ORDER BY period, furniture_type;
    ";
    
    $monthly_sql = "
        SELECT
            DATE_FORMAT(q.created_at, '%Y-%m') AS period,
            i.furniture AS furniture_type,
            COUNT(*) AS number_ordered,
            ROUND(SUM(i.item_price), 2) AS total_price_ordered
        FROM items i
        JOIN quotes q ON i.quote_id = q.quote_id
        JOIN orders o ON q.quote_id = o.quote_id
        WHERE o.order_phase = 'received'
        GROUP BY period, furniture_type
        ORDER BY period, furniture_type;
    ";
    
    $yearly_sql = "
        SELECT
            YEAR(q.created_at) AS period,
            i.furniture AS furniture_type,
            COUNT(*) AS number_ordered,
            ROUND(SUM(i.item_price), 2) AS total_price_ordered
        FROM items i
        JOIN quotes q ON i.quote_id = q.quote_id
        JOIN orders o ON q.quote_id = o.quote_id
        WHERE o.order_phase = 'received'
        GROUP BY period, furniture_type
        ORDER BY period, furniture_type;
    ";    
    // Initialize variables to store query results
    $daily_results = [];
    $weekly_results = [];
    $monthly_results = [];
    $yearly_results = [];
    
    try {
        // Execute daily query
        $stmt = $conn->query($daily_sql);
        $daily_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Execute weekly query
        $stmt = $conn->query($weekly_sql);
        $weekly_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Execute monthly query
        $stmt = $conn->query($monthly_sql);
        $monthly_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Execute yearly query
        $stmt = $conn->query($yearly_sql);
        $yearly_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
        ?>
                <div class="daily_container tab-content" id="daily_container" data-tab="daily">
                    <div class="daily">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Daily Statistics</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Sales</td>
                                    <td><?= '₱ ' . $total_revenue_today ?></td>
                                </tr>
                                <tr>
                                    <td>Total Orders</td>
                                    <td><?= $new_orders_today ?></td>
                                </tr>
                                <tr>
                                    <td>Total Finished Orders</td>
                                    <td><?= $completed_orders_today ?></td>
                                </tr>
                                <tr>
                                    <td>Average Ratings</td>
                                    <td>
                                        <?= $avg_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Customers</td>
                                    <td><?= $new_customers_today ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Average Star Rating</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Made To Order</td>
                                    <td>
                                        <?= $mto_daily_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Repair</td>
                                    <td>
                                        <?= $repair_daily_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="breakdown_of_furnitures_container">

                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daily_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daily_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>





                <div class="weekly_container tab-content" id="weekly_container" data-tab="weekly">
                    <div class="weekly">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Weekly Statistics</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Sales</td>
                                    <td><?= '₱ ' . $total_revenue_current_week ?></td>
                                </tr>
                                <tr>
                                    <td>Total Orders</td>
                                    <td><?= $new_orders_current_week ?></td>
                                </tr>
                                <tr>
                                    <td>Total Finished Orders</td>
                                    <td><?= $completed_orders_current_week ?></td>
                                </tr>
                                <tr>
                                    <td>Average Ratings</td>
                                    <td>
                                        <?= $avg_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Customers</td>
                                    <td><?= $new_customers_current_week ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Average Star Rating</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Made To Order</td>
                                    <td>
                                        <?= $mto_weekly_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Repair</td>
                                    <td>
                                        <?= $repair_weekly_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="breakdown_of_furnitures_container">
                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekly_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekly_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="monthly_container tab-content" id="monthly_container" data-tab="monthly">
                    <div class="monthly">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Monthly Statistics</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Sales</td>
                                    <td><?= '₱ ' . $total_revenue_current_month ?></td>
                                </tr>
                                <tr>
                                    <td>Total Orders</td>
                                    <td><?= $new_orders_current_month ?></td>
                                </tr>
                                <tr>
                                    <td>Total Finished Orders</td>
                                    <td><?= $completed_orders_current_month ?></td>
                                </tr>
                                <tr>
                                    <td>Average Ratings</td>
                                    <td>
                                        <?= $avg_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Customers</td>
                                    <td><?= $new_customers_current_month ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Average Star Rating</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Made To Order</td>
                                    <td>
                                        <?= $mto_monthly_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Repair</td>
                                    <td>
                                        <?= $repair_monthly_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="breakdown_of_furnitures_container">
                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monthly_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monthly_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="yearly_container tab-content" id="yearly_container" data-tab="yearly">
                    <div class="yearly">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Yearly Statistics</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Sales</td>
                                    <td><?= '₱ ' . $total_revenue_current_year ?></td>
                                </tr>
                                <tr>
                                    <td>Total Orders</td>
                                    <td><?= $new_orders_current_year ?></td>
                                </tr>
                                <tr>
                                    <td>Total Finished Orders</td>
                                    <td><?= $completed_orders_current_year ?></td>
                                </tr>
                                <tr>
                                    <td>Average Ratings</td>
                                    <td>
                                        <?= $avg_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <td>New Customers</td>
                                    <td><?= $new_customers_current_year ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Average Star Rating</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Made To Order</td>
                                    <td>
                                        <?= $mto_yearly_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Repair</td>
                                    <td>
                                        <?= $repair_yearly_average_rating ?>
                                        <img src="../websiteimages/starimg.png" alt="Average Ratings" width="15%" height="15%">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="breakdown_of_furnitures_container">
                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($yearly_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <table class="order-table-breakdown">
                            <thead>
                                <tr>
                                    <th>Furniture Type</th>
                                    <th>Number Ordered</th>
                                    <th>Total Price Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($yearly_results as $row): ?>
                                <tr>
                                    <td><?php echo $row['furniture_type']; ?></td>
                                    <td><?php echo $row['number_ordered']; ?></td>
                                    <td><?php echo 'Php ' . number_format($row['total_price_ordered']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="order-list-reports" id="order-list">
                <p class="main-title">Order Filtering</p>
                <hr class="divider1">
                <form class="order-filters" method="get" action="">
                    <div class="order-filter-search">
                        <select name="search-order" id="" class="search-selector">
                            <option value="order_id">Order ID</option>
                            <option value="customer_name">Cust. Name</option>
                            <option value="item">Item</option>
                        </select>
                        <hr class="filter-divider">
                        <div class="input-search">
                            <input type="text" name="search-input" id="" size="12" placeholder="Search">
                            <img src="../websiteimages/icons/Search.svg" alt="">
                        </div>
                    </div>
                    <div class="filter-type selector-container">
                        <select name="service-type" id="" class="selector">
                            <option value="default">Type</option>
                            <option value="mto">MTO</option>
                            <option value="repair">Repair</option>
                        </select>
                    </div>
                    <div class="filter-prod-status selector-container">
                        <select name="order-prod-status" id="" class="selector">
                            <option value="default">Prod. Status</option>
                            <option value="pending_downpayment">Pending Downpayment</option>
                            <option value="ready_for_pickup">Ready for Pickup</option>
                            <option value="in_production">In Production</option>
                            <option value="pending_fullpayment">Pending Fullpayment</option>
                            <option value="out_for_delivery">Delivering</option>
                            <option value="received">Received</option>
                        </select>
                    </div>
                    <div class="filter-payment-status selector-container">
                        <select name="order-payment-status" id="" class="selector">
                            <option value="default">Payment</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="partially_paid">Partially Paid</option>
                            <option value="fully_paid">Fully Paid</option>
                        </select>
                    </div>
                    <div class="filter-sort selector-container">
                        <select name="order-sort" id="" class="selector">
                            <option value="default">Sort By</option>
                            <option value="order_id">Order ID</option>
                            <option value="est_completion_date">Est. Delivery Date</option>
                            <option value="item">Item</option>
                        </select>
                    </div>
                    <div class="filter-date selector-container">
                        <select id="date-range" name="date-range" class="selector">
                            <option value="default">Pick Date</option>
                            <option value="today">Today</option>
                            <option value="this-week">This Week</option>
                            <option value="this-month">This Month</option>
                            <option value="custom">Custom</option>
                        </select>
                        <div id="custom-date" class="custom-date" style="display: none;">
                            <input type="date" id="start-date" name="start-date">
                            <input type="date" id="end-date" name="end-date">
                        </div>
                    </div>
                    <input type="submit" value="Filter" class="filter">
                </form>
                <div class="selected-multiple">
                    <img src="/websiteimages/icons/close-icon-gray.svg" alt="" class="close-icon">
                    <span class="selected-count"></span><span>selected</span>
                    <span class="advance-next">Advance To Next Phase<img src="/websiteimages/icons/arrow-right.svg" alt=""></span>
                </div>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Customer Name</th>
                            <th>ITEM/S</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Placement Date</th>
                            <th>Prod. Status</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($orders AS $order) {
                                $price = is_null($order['price']) ? "N/A" : "₱" . number_format($order['price']);

                                $date = date('M d, Y', strtotime($order['placement_date']));

                                $prod_status = str_replace("_", "-", $order['prod_status']);
                                $prod_status_text = ucwords(str_replace("-", " ", $prod_status));

                                $payment_status = str_replace("_", "-", $order['payment_status']);
                                $payment_status_text = ucwords(str_replace("_", " ", $order['payment_status'])); 
                                $type = ($order['order_type'] === "mto") ? "Made-To-Order" : "Repair";
                                $order_status = str_replace("_", "-", $order['prod_status']);
                                $statuses = [
                                    "pending-downpayment" => "Pending Downpayment",
                                    "awating-furniture" => "Awaiting Furniture",
                                    "in-production" => "In Production",
                                    "pending-fullpayment" => "Pending Fullpayment",
                                    "out-for-delivery" => "Out for Delivery",
                                    "received" => "Received",
                                ];
                                $prod_status_options = "";

                                if($order_status === "cancelled") {
                                    
                                } else {
                                    $include = false;
                                    foreach ($statuses as $status => $status_text) {
                                        if ($include) {
                                            $prod_status_options .= "<option value='{$status}'>{$status_text}</option>";
                                        }
                                        if ($status === $order_status) {
                                            if($status === "ready-for-pickup" && $type === "MTO") {
                                                continue;
                                            }
                                            $prod_status_options .= "<option value='{$status}'>{$status_text}</option>";
                                            $include = true;
                                        }
                                    }
                                }

                                $item = ucwords($order['item']);
                                if (strlen($item) > 12) {
                                    $item = substr($item, 0, 12) . '...';
                                }
                                echo "
                                <tr data-id='{$order['order_id']}'>
                                    <td>{$order['order_id']}</td>
                                    <td>{$order['customer_name']}</td>
                                    <td>{$item}</td>
                                    <td>{$type}</td>
                                    <td>{$price}</td>
                                    <td>{$date}</td>
                                    <td>
                                        <span data-prod-status='{$prod_status}'>{$prod_status_text}</span>
                                    </td>
                                    <td class='payment-status status'>
                                        <span data-payment='{$payment_status}'>{$payment_status_text}</span>
                                    </td>
                                </tr>
                                ";
                            }
                        ?>
                    </tbody>
                </table>
                <hr class="divider1">
                <div class="query-records">
                    <div class="record-count">
                        Showing
                        </span> of <span><?= $total_records ?></span> results
                    </div>
                </div>
                <div class="button_containers">
                    <button id="print" class="Print_button">
                        <img src="../websiteimages/Print_icon.png" class="print">
                        Print
                    </button>

                    <button id="download_button" class="Download_button">
                        <img src="../websiteimages/Download_icon.png" alt="" class="Download">
                        Download
                    </button>
                </div>
            </div>
        </div>


        <script src="../js/admin/order.js"></script>
        <script src="../js/sidebar.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
        <script>
            const save_pdf = document.getElementById('print');

            save_pdf.addEventListener('click', function() {
                window.print()
            })
        </script>

        <script>
            let content = document.body;
            download_button.addEventListener('click', async function() {
                const filename = 'table_data.pdf';

                // Create a new style element
                const style = document.createElement('style');
                // Set the CSS rules
                style.innerHTML = `

            .admin-sidebar {
                visibility: hidden;
            }

            .selector-container {
                padding: 0.2rem 0.2rem; 
            }

            .content-container,
            .content-container * {
                visibility: visible;
            }

            .content-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
            }

            .divider{
                display:none;
            }

            .filter{
                display: none;
            }

            .Print_button{
                display: none;
            }

            .Download_button{
                display: none;
            }

            `;
                // Append the style element to the content element
                content.appendChild(style);



                try {
                    const opt = {
                        margin: 1,
                        filename: filename,
                        image: {
                            type: 'jpeg',
                            quality: 100
                        },
                        html2canvas: {
                            scale: 2
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'letter',
                            orientation: 'landscape'
                        }

                    };
                    await html2pdf().set(opt).from(content).save();
                } catch (error) {
                    console.error('Error:', error.message);
                }

                // Remove the style element after converting to PDF
                content.removeChild(style);
            });


            document.addEventListener('DOMContentLoaded', function() {
                // Get references to elements
                const dateRangeSelect = document.getElementById('date-range');
                const customDateDiv = document.getElementById('custom-date');

                // Function to toggle display of custom date inputs
                function toggleCustomDateInputs() {
                    const selectedValue = dateRangeSelect.value;
                    if (selectedValue === 'custom') {
                        customDateDiv.style.display = 'block';
                    } else {
                        customDateDiv.style.display = 'none';
                    }
                }

                // Initial call to set initial state based on default selection
                toggleCustomDateInputs();

                // Event listener for when dropdown value changes
                dateRangeSelect.addEventListener('change', toggleCustomDateInputs);
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdown = document.getElementById('tab-dropdown');
                const tabContents = document.querySelectorAll('.tab-content');

                dropdown.addEventListener('change', function() {
                    const target = dropdown.value;

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Display the selected tab content
                    const selectedTab = document.getElementById(`${target}_container`);
                    if (selectedTab) {
                        selectedTab.classList.add('active');
                    }
                });

                // Trigger change event to show the initial content
                dropdown.dispatchEvent(new Event('change'));
            });
        </script>
</body>

</html>