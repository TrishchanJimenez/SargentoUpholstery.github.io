<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/orders_and_quotes.css">
    <script src="/js/my/orders_and_quotes.js"></script>
    <title>Sargento Upholstery</title>
</head>

<body>
    <?php 
        require_once('../database_connection.php');
        require_once('../header.php'); 

        // Pagination logic
        $results_per_page = 5;  // Number of results per page

        // Get the current page number from the URL, if none exists set to 1
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $results_per_page;

        // Fetch the quotes for the current page
        $quote_query = "SELECT * FROM `quotes` WHERE `customer_id` = :customer_id LIMIT :limit OFFSET :offset";
        $quote_stmt = $conn->prepare($quote_query);
        $quote_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
        $quote_stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
        $quote_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $quote_stmt->execute();
        $quotes = $quote_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get the total number of quotes
        $total_quotes_query = "SELECT COUNT(*) FROM `quotes` WHERE `customer_id` = :customer_id";
        $total_quotes_stmt = $conn->prepare($total_quotes_query);
        $total_quotes_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
        $total_quotes_stmt->execute();
        $total_quotes = $total_quotes_stmt->fetchColumn();
        $total_pages = ceil($total_quotes / $results_per_page);
    ?>
    <div class="onq">
        <h1 class="onq__title">My Orders and Quotes</h1>
        <div class="onq__tab-button-container">
            <button class="onq__tab-button onq__tab-button--quotes">All Quotes</button>
            <button class="onq__tab-button onq__tab-button--orders">All Orders</button>
        </div>
        <div class="quote-buttons">
            <form class="order-filters" method="get" action="">
                <table class="filter-table">
                    <tr>
                        <td>
                            <div class="input-search">
                                <input type="text" name="search-input" size="12" placeholder="Search">
                                <img src="../websiteimages/icons/Search.svg" alt="">
                            </div>
                        </td>
                        <td>
                            <select name="service-type" class="selector">
                                <option value="default">Type</option>
                                <option value="mto">MTO</option>
                                <option value="repair">Repair</option>
                            </select>
                        </td>
                        <td>
                            <select name="quote-status" class="selector">
                                <option value="default">Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="accepted">Accepted</option>
                                <option value="cancelled-rejected">Cancelled/Rejected</option>
                            </select>
                        </td>
                        <td>
                            <input type="submit" value="Filter">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="onq__tab onq__tab--quotes">
            <table class="onq__table">
                <thead>
                    <tr>
                        <th class="onq__th onq__th--quote onq__th--title" colspan="6">All Quotes</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="onq__th--corner">Quote ID</th>
                        <th class="onq__th onq__th--quote">Furniture Type</th>
                        <th class="onq__th onq__th--quote">Service Type</th>
                        <th class="onq__th onq__th--quote">Quantity</th>
                        <th class="onq__th onq__th--quote">Status</th>
                        <th class="onq__th--corner"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($quotes) {
                            foreach ($quotes as $row) {
                                echo '
                                    <tr>
                                        <td class="onq__td onq__td--quote">' . htmlspecialchars($row["quote_id"]) . '</td>
                                        <td class="onq__td onq__td--alt onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture_type"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
                                        <td class="onq__td onq__td--alt onq__td--quote">' . htmlspecialchars($row["quantity"] ?? 'N/A') . ' item/s</td>
                                        <td class="onq__td onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["quote_status"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td--edge">
                                            <a href="quotes.php?quote_id=' . htmlspecialchars($row["quote_id"]) . '">
                                                >
                                            </a>
                                        </td>
                                    </tr>
                                ';
                            }
                        }
                    ?>
                </tbody>
            </table>
            <!-- Pagination for Quotes -->
            <div class="pagination">
                <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                    <button onclick="window.location.href='orders_and_quotes.php?page=<?php echo $page; ?>'" class="pagination__button"><?php echo $page; ?></button>
                <?php endfor; ?>
            </div>
        </div>
        <div class="onq__tab onq__tab--orders">
            <?php
                // Pagination logic for orders
                $page_orders = isset($_GET['page_orders']) ? intval($_GET['page_orders']) : 1;
                $offset_orders = ($page_orders - 1) * $results_per_page;

                // Fetch the orders for the current page
                $order_query = "
                    SELECT 
                        *
                    FROM 
                        `orders` o
                            INNER JOIN
                        `quotes` q
                            USING (quote_id)
                    WHERE 
                        `user_id` = :user_id
                    ORDER BY
                        `last_updated` DESC
                    LIMIT :limit OFFSET :offset
                ";
                $order_stmt = $conn->prepare($order_query);
                $order_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $order_stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
                $order_stmt->bindParam(':offset', $offset_orders, PDO::PARAM_INT);
                $order_stmt->execute();
                $orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get the total number of orders
                $total_orders_query = "SELECT COUNT(*) FROM `orders` WHERE `user_id` = :user_id";
                $total_orders_stmt = $conn->prepare($total_orders_query);
                $total_orders_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $total_orders_stmt->execute();
                $total_orders = $total_orders_stmt->fetchColumn();
                $total_pages_orders = ceil($total_orders / $results_per_page);
            ?>
            <table class="onq__table">
                <thead>
                    <tr>
                        <th class="onq__th onq__th--order onq__th--title" colspan="8">All Orders</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="onq__th--corner">Order ID</th>
                        <th class="onq__th onq__th--order">Furniture Type</th>
                        <th class="onq__th onq__th--order">Service Type</th>
                        <th class="onq__th onq__th--order">Quantity</th>
                        <th class="onq__th onq__th--order">Delivery Method</th>
                        <th class="onq__th onq__th--order">Price</th>
                        <th class="onq__th onq__th--order">Status</th>
                        <th class="onq__th--corner"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($orders) {
                            foreach ($orders as $row) {
                                echo '
                                    <tr>
                                        <td class="onq__td">' . htmlspecialchars($row["order_id"]) . '</td>
                                        <td class="onq__td onq__td--alt onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture_type"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
                                        <td class="onq__td onq__td--alt onq__td--order">' . htmlspecialchars($row["quantity"] ?? 'N/A') . ' item/s</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["del_method"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--alt onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["quoted_price"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["order_status"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td--edge">
                                            <a href="orders.php?order_id=' . htmlspecialchars($row["order_id"]) . '">
                                                >
                                            </a>
                                        </td>
                                    </tr>
                                ';
                            }
                        }
                    ?>
                </tbody>
            </table>
            <!-- Pagination for Orders -->
            <div class="pagination">
                <?php for ($page_orders = 1; $page_orders <= $total_pages_orders; $page_orders++): ?>
                    <button onclick="window.location.href='orders_and_quotes.php?page_orders=<?php echo $page_orders; ?>'" class="pagination__button"><?php echo $page_orders; ?></button>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <script src="/js/globals.js"></script>
</body>
</html>
