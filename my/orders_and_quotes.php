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
        $results_per_page = 3;  // Number of results per page

        // Get the current page number from the URL, if none exists set to 1
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $results_per_page;

        // Get filter parameters
        $item_type = isset($_GET['item_type']) ? $_GET['item_type'] : 'default';
        $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : 'default';
        $status = isset($_GET['status']) ? $_GET['status'] : 'default';

        // Build the filter query
        $quote_query = "SELECT * FROM `quotes` q LEFT JOIN items i USING(quote_id) WHERE `customer_id` = :customer_id";
        if ($item_type != 'default') {
            $quote_query .= " AND i.item_type = :item_type";
        }
        if ($service_type != 'default') {
            $quote_query .= " AND q.service_type = :service_type";
        }
        if ($status != 'default') {
            $quote_query .= " AND q.quote_status = :status";
        }
        $quote_query .= " ORDER BY i.item_id DESC LIMIT :limit OFFSET :offset";

        $quote_stmt = $conn->prepare($quote_query);
        $quote_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
        if ($item_type != 'default') {
            $quote_stmt->bindParam(':item_type', $item_type, PDO::PARAM_STR);
        }
        if ($service_type != 'default') {
            $quote_stmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
        }
        if ($status != 'default') {
            $quote_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        $quote_stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
        $quote_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $quote_stmt->execute();
        $quotes = $quote_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get the total number of quotes
        $total_quotes_query = "SELECT COUNT(*) FROM `quotes` q LEFT JOIN items i USING(quote_id) WHERE `customer_id` = :customer_id";
        if ($item_type != 'default') {
            $total_quotes_query .= " AND i.item_type = :item_type";
        }
        if ($service_type != 'default') {
            $total_quotes_query .= " AND q.service_type = :service_type";
        }
        if ($status != 'default') {
            $total_quotes_query .= " AND q.quote_status = :status";
        }

        $total_quotes_stmt = $conn->prepare($total_quotes_query);
        $total_quotes_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
        if ($item_type != 'default') {
            $total_quotes_stmt->bindParam(':item_type', $item_type, PDO::PARAM_STR);
        }
        if ($service_type != 'default') {
            $total_quotes_stmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
        }
        if ($status != 'default') {
            $total_quotes_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
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
        <div class="onq__tab onq__tab--quotes" style="display:flex; flex-direction:column; align-items:center;">
            <form class="order-filters" method="get" action="">
                <table class="filter-table">
                    <tr>
                        <td>
                            <input type="text" name="order_item_type" class="selector" placeholder="Item Type" value="<?= htmlspecialchars($order_item_type) ?>">
                        </td>
                        <td>
                            <select name="service_type" class="selector">
                                <option value="default">Service Type</option>
                                <option value="mto" <?= $service_type == 'mto' ? 'selected' : '' ?>>MTO</option>
                                <option value="repair" <?= $service_type == 'repair' ? 'selected' : '' ?>>Repair</option>
                            </select>
                        </td>
                        <td>
                            <select name="status" class="selector">
                                <option value="default">All Status</option>
                                <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="accepted" <?= $status == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <input type="submit" value="Filter">
                        </td>
                    </tr>
                </table>
            </form>
            <table class="onq__table">
                <thead>
                    <tr>
                        <th class="onq__th onq__th--quote onq__th--title" colspan="6">All Quotes</th>
                    </tr>
                </thead>
                <!-- Table headers of all quotes -->
                <thead class="onq__thead onq__thead--quotes">
                    <tr class="onq__tr onq__tr--quotes">
                        <th class="onq__th onq__th--quotes onq__tcorner">Quote ID</th>
                        <th class="onq__th onq__th--quotes">Item Type</th>
                        <th class="onq__th onq__th--quotes">Service Type</th>
                        <th class="onq__th onq__th--quotes">Status</th>
                        <th class="onq__th onq__th--quotes onq__tcorner">View</th>
                    </tr>
                </thead>
                <tbody class="onq__tbody onq__tbody--quotes">
                    <?php
                        if($quotes) {
                            foreach ($quotes as $row) {
                                echo '
                                    <tr>
                                        <td class="onq__td onq__td--alt onq__td--quote">' . htmlspecialchars($row["quote_id"]) . '</td>
                                        <td class="onq__td onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--alt onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
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
                    <?php
                        // Check if $_GET['page'] is set and matches the loop iteration
                        $active_class = (isset($_GET['page']) && $page == $_GET['page']) ? 'active' : '';
                        // Add the active class if it's the first page and $_GET['page'] is not set
                        if (!isset($_GET['page']) && $page === 1) {
                            $active_class = 'active';
                        }
                    ?>
                    <button onclick="window.location.href='orders_and_quotes.php?page=<?php echo $page; ?>&item_type=<?php echo $item_type; ?>&service_type=<?php echo $service_type; ?>&status=<?php echo $status; ?>'" class="pagination__button <?php echo $active_class; ?>"><?php echo $page; ?></button>
                <?php endfor; ?>
            </div>
        </div>
        <div class="onq__tab onq__tab--orders" style="display:flex; flex-direction:column; align-items:center;">
            <?php
                // Pagination logic for orders
                $page_orders = isset($_GET['page_orders']) ? intval($_GET['page_orders']) : 1;
                $offset_orders = ($page_orders - 1) * $results_per_page;

                // Fetch the orders for the current page
                $order_query = " SELECT * FROM `quotes` q LEFT JOIN `orders` o USING (quote_id) WHERE `customer_id` = :customer_id ORDER BY `q.updated_at` DESC LIMIT :limit OFFSET :offset";
                $order_stmt = $conn->prepare($order_query);
                $order_stmt->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
                // if ($order_item_type != 'default') {
                //     $order_stmt->bindParam(':order_item_type', $order_item_type, PDO::PARAM_STR);
                // }
                // if ($order_service_type != 'default') {
                //     $order_stmt->bindParam(':order_service_type', $order_service_type, PDO::PARAM_STR);
                // }
                // if ($order_status != 'default') {
                //     $order_stmt->bindParam(':order_status', $order_status, PDO::PARAM_STR);
                // }
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
            <form class="order-filters" method="get" action="">
                <table class="filter-table">
                    <tr>
                        <td>
                            <select name="order_item_type" class="selector">
                                <option value="default">Item Type</option>
                                <option value="sofa" <?= $order_item_type == 'sofa' ? 'selected' : '' ?>>Sofa</option>
                                <option value="chair" <?= $order_item_type == 'chair' ? 'selected' : '' ?>>Chair</option>
                                <!-- Add other item types here -->
                            </select>
                        </td>
                        <td>
                            <select name="order_service_type" class="selector">
                                <option value="default">Service Type</option>
                                <option value="mto" <?= $order_service_type == 'mto' ? 'selected' : '' ?>>MTO</option>
                                <option value="repair" <?= $order_service_type == 'repair' ? 'selected' : '' ?>>Repair</option>
                            </select>
                        </td>
                        <td>
                            <select name="order_status" class="selector">
                                <option value="default">Status</option>
                                <option value="pending" <?= $order_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $order_status == 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="accepted" <?= $order_status == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <input type="submit" value="Filter">
                        </td>
                    </tr>
                </table>
            </form>
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
                <?php for ($i = 1; $i <= $total_pages_orders; $i++): ?>
                    <?php
                        // Check if $_GET['page_orders'] is set and matches the loop iteration
                        $active_class = (isset($_GET['page_orders']) && $i == $_GET['page_orders']) ? 'active' : '';
                        // Add the active class if it's the first page and $_GET['page_orders'] is not set
                        if (!isset($_GET['page_orders']) && $i === 1) {
                            $active_class = 'active';
                        }
                    ?>
                    <button onclick="window.location.href='orders_and_quotes.php?page_orders=<?php echo $i; ?>&order_item_type=<?php echo $order_item_type; ?>&order_service_type=<?php echo $order_service_type; ?>&order_status=<?php echo $order_status; ?>'" class="pagination__button <?php echo $active_class; ?>"><?php echo $i; ?></button>
                <?php endfor; ?>
            </div>
        </div>

    </div>
    <script src="/js/globals.js"></script>
</body>
</html>
