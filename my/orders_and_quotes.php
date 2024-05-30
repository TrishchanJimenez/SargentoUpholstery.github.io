<?php
    session_start(); // Start the session at the beginning

    require_once('../database_connection.php');
    require_once('../header.php');

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Pagination logic
    $results_per_page = 6; // Number of results per page

    // Get the current page number from the URL, if none exists set to 1
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $results_per_page;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/orders_and_quotes.css">
    <title>Sargento Upholstery</title>
</head>

<body>
    <div class="tab-button-container">
        <button class="tab active" onclick="openTab('tab--quotes', this)">QUOTES</button>
        <button class="tab" onclick="openTab('tab--orders', this)">ORDERS</button>
    </div>

    <div id="tab--quotes" class="tabcontent" style="display: block;">
        <?php
            // Get filter parameters
            $item_type = isset($_GET['item_type']) ? $_GET['item_type'] : 'default';
            $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : 'default';
            $status = isset($_GET['status']) ? $_GET['status'] : 'default';

            // Build the filter query
            $quote_query = "
                SELECT 
                    * 
                FROM 
                `quotes` q 
                    JOIN 
                `items` i ON q.quote_id = i.quote_id
                WHERE 
                    q.customer_id = :customer_id
            ";
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

            try {
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
            } catch (PDOException $e) {
                // Handle database errors here
                echo "Error: " . $e->getMessage();
            }
        ?>
        <table class="onq__table">
            <!-- Table headers of all quotes -->
            <thead class="table-head">
                <tr class="table-header">
                    <th>Quote ID</th>
                    <th>Item Type</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($quotes) {
                    foreach ($quotes as $row) {
                        echo '
                            <tr>
                                <td class="table-data">' . htmlspecialchars($row["quote_id"]) . '</td>
                                <td class="table-data">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture"] ?? 'N/A'))) . '</td>
                                <td class="table-data">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
                                <td class="table-data">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["quote_status"] ?? 'N/A'))) . '</td>
                                <td class="table-data">
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
        <div class="pagination">
            <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
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
    <div id="tab--orders" class="tabcontent" style="display: none;">
        <?php
            // Get filter parameters
            $item_type = isset($_GET['item_type']) ? $_GET['item_type'] : 'default';
            $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : 'default';
            $order_phase = isset($_GET['order_phase']) ? $_GET['order_phase'] : 'default';

            // Build the filter query
            $order_query = "
                SELECT 
                    * 
                FROM 
                    `orders` o 
                        JOIN
                    `quotes` q ON o.quote_id = q.quote_id
                        JOIN 
                    `items` i ON q.quote_id = i.quote_id
                WHERE 
                    `customer_id` = :customer_id
            ";
            if ($item_type != 'default') {
                $order_query .= " AND i.furniture LIKE ':item_type'";
            }
            if ($service_type != 'default') {
                $order_query .= " AND q.service_type = :service_type";
            }
            if ($status != 'default') {
                $order_query .= " AND q.order_phase = :order_phase";
            }
            $order_query .= " ORDER BY i.item_id DESC LIMIT :limit OFFSET :offset";

            try {
                $order_stmt = $conn->prepare($order_query);
                $order_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                if ($item_type != 'default') {
                    $order_stmt->bindParam(':item_type', $item_type, PDO::PARAM_STR);
                }
                if ($service_type != 'default') {
                    $order_stmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
                }
                if ($status != 'default') {
                    $order_stmt->bindParam(':order_phase', $status, PDO::PARAM_STR);
                }
                $order_stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
                $order_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                $order_stmt->execute();
                $orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get the total number of orders
                $total_orders_query = "
                    SELECT 
                        COUNT(*) 
                    FROM 
                        `orders` o 
                            JOIN
                        `quotes` q ON o.quote_id = q.quote_id   
                    WHERE 
                        q.customer_id = :customer_id
                ";
                if ($item_type != 'default') {
                    $total_orders_query .= " AND i.furniture = :item_type";
                }
                if ($service_type != 'default') {
                    $total_orders_query .= " AND q.service_type = :service_type";
                }
                if ($status != 'default') {
                    $total_orders_query .= " AND q.order_phase = :order_phase";
                }

                $total_orders_stmt = $conn->prepare($total_orders_query);
                $total_orders_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                if ($item_type != 'default') {
                    $total_orders_stmt->bindParam(':item_type', $item_type, PDO::PARAM_STR);
                }
                if ($service_type != 'default') {
                    $total_orders_stmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
                }
                if ($status != 'default') {
                    $total_orders_stmt->bindParam(':order_phase', $order_phase, PDO::PARAM_STR);
                }
                $total_orders_stmt->execute();
                $total_orders = $total_orders_stmt->fetchColumn();
                $total_pages = ceil($total_orders / $results_per_page);
            } catch (PDOException $e) {
                // Handle database errors here
                echo "Error: " . $e->getMessage();
            }
        ?>
        <table class="onq__table">
            <!-- Table headers of all orders -->
            <thead class="table-head">
                <tr class="table-header">
                    <th>Order ID</th>
                    <th>Item Type</th>
                    <th>Service Type</th>
                    <th>Order Status</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($orders) {
                        foreach ($orders as $row) {
                            echo '
                                <tr>
                                    <td class="table-data">' . htmlspecialchars($row["order_id"]) . '</td>
                                    <td class="table-data">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture"] ?? 'N/A'))) . '</td>
                                    <td class="table-data">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
                                    <td class="table-data">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["order_phase"] ?? 'N/A'))) . '</td>
                                    <td class="table-data">
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
    </div>
</body>

<script>
    function openTab(tabName, elmnt) {
        // Hide all tab content
        var tabcontent = document.getElementsByClassName("tabcontent");
        for (var i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Remove the "active" class from all tabs
        var tabs = document.getElementsByClassName("tab");
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove("active");
        }

        // Show the selected tab content
        document.getElementById(tabName).style.display = "block";

        // Add the "active" class to the clicked tab
        elmnt.classList.add("active");
    }
</script>
<script src="/js/globals.js"></script>

</html>