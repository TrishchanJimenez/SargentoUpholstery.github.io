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
    $results_per_page = 3; // Number of results per page

    // Get the current page number from the URL, if none exists set to 1
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'quotes';
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
    <div class="onq">
        <div id="tab--quotes" class="onq__content" style="<?= ($tab === 'quotes') ? 'display: block;' : 'display:none ' ?>">
            <?php
                // Get filter parameters
                $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : 'default';
                $status = isset($_GET['status']) ? $_GET['status'] : 'default';

                // Build the filter query
                $quote_query = "
                        SELECT 
                            * 
                        FROM 
                            `quotes` q 
                        WHERE 
                            q.customer_id = :customer_id
                    ";
                if ($service_type != 'default') {
                    $quote_query .= " AND q.service_type = :service_type";
                }
                if ($status != 'default') {
                    $quote_query .= " AND q.quote_status = :status";
                }

                $quote_query .= " ORDER BY q.quote_id DESC LIMIT :limit OFFSET :offset";

                try {
                    $quote_stmt = $conn->prepare($quote_query);
                    $quote_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
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
                    $total_quotes_query = "
                        SELECT COUNT(*) FROM `quotes` q WHERE `customer_id` = :customer_id
                    ";
                    if ($service_type != 'default') {
                        $total_quotes_query .= " AND q.service_type = :service_type";
                    }
                    if ($status != 'default') {
                        $total_quotes_query .= " AND q.quote_status = :status";
                    }

                    $total_quotes_stmt = $conn->prepare($total_quotes_query);
                    $total_quotes_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
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
            <div class="onq__table-wrapper">
                <div class="onq__header">
                    <div class="tab__container">
                        <button class="tab--quotes tab <?= ($tab === 'quotes') ? 'active' : '' ?>" onclick="openTab('tab--quotes', this)">QUOTES</button>
                        <button class="tab--orders tab <?= ($tab === 'orders') ? 'active' : '' ?>" onclick="openTab('tab--orders', this)">ORDERS</button>
                    </div>
                    <div class="filter__container">
                        <form class="filter" method="get">
                            <table class="filter__table">
                                <tr class="filter__tr">
                                    <td class="filter__td">
                                        <select name="service_type" class="selector">
                                            <option value="default">Service Type</option>
                                            <option value="mto" <?= $service_type == 'mto' ? 'selected' : '' ?>>MTO</option>
                                            <option value="repair" <?= $service_type == 'repair' ? 'selected' : '' ?>>Repair</option>
                                        </select>
                                    </td>
                                    <td class="filter__td">
                                        <select name="status" class="selector">
                                            <option value="default">All Status</option>
                                            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="accepted" <?= $status == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                            <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                            <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="filter__td">
                                        <input class="submit-filter" type="submit" value="Filter">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <table class="onq__table">
                    <!-- Table headers of all quotes -->
                    <thead class="onq__thead">
                        <tr class="onq__tr   onq__tr--th">
                            <th class="onq__th">Quote ID</th>
                            <th class="onq__th">Item Type</th>
                            <th class="onq__th">Service Type</th>
                            <th class="onq__th">Status</th>
                            <th class="onq__th">View</th>
                        </tr>
                    </thead>
                    <tbody class="onq__tbody">
                        <?php if ($quotes) : ?>
                            <?php foreach ($quotes as $row) : ?>
                                <tr class="onq__tr   onq__tr--head">
                                    <td class="onq__td"> <?= htmlspecialchars($row["quote_id"]) ?> </td>
                                    <td class="onq__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture"] ?? 'N/A'))) ?> </td>
                                    <td class="onq__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?> </td>
                                    <td class="onq__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["quote_status"] ?? 'N/A'))) ?> </td>
                                    <td class="onq__td">
                                        <a href="quotes.php?quote_id=<?= htmlspecialchars($row["quote_id"]) ?> ">
                                            >
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
                    <button onclick="window.location.href='orders_and_quotes.php?page=<?php echo $page; ?>&service_type=<?php echo $service_type; ?>&status=<?php echo $status; ?>'" class="pagination__button <?php echo $active_class; ?>"><?php echo $page; ?></button>
                <?php endfor; ?>
            </div>
        </div>
        <div id="tab--orders" class="onq__content" style="<?= ($tab === 'orders') ? 'display: block;' : 'display:none ' ?>">
            <?php
                // Get filter parameters
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
                        WHERE 
                            `customer_id` = :customer_id
                    ";
                if ($service_type != 'default') {
                    $order_query .= " AND q.service_type = :service_type";
                }
                if ($order_phase != 'default') {
                    $order_query .= " AND o.order_phase = :order_phase";
                }
                $order_query .= " ORDER BY O.order_id DESC LIMIT :limit OFFSET :offset";

                try {
                    $order_stmt = $conn->prepare($order_query);
                    $order_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                    if ($service_type != 'default') {
                        $order_stmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
                    }
                    if ($order_phase != 'default') {
                        $order_stmt->bindParam(':order_phase', $order_phase, PDO::PARAM_STR);
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
                    if ($service_type != 'default') {
                        $total_orders_query .= " AND q.service_type = :service_type";
                    }
                    if ($order_phase != 'default') {
                        $total_orders_query .= " AND o.order_phase = :order_phase";
                    }

                    $total_orders_stmt = $conn->prepare($total_orders_query);
                    $total_orders_stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                    if ($service_type != 'default') {
                        $total_orders_stmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
                    }
                    if ($order_phase != 'default') {
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
            <div class="onq__table-wrapper">
                <div class="onq__header">
                    <div class="tab__container">
                        <button class="tab--quotes tab <?= ($tab === 'quotes') ? 'active' : '' ?>" onclick="openTab('tab--quotes', this)">QUOTES</button>
                        <button class="tab--orders tab <?= ($tab === 'orders') ? 'active' : '' ?>" onclick="openTab('tab--orders', this)">ORDERS</button>
                    </div>
                    <div class="filter-container">
                        <form class="filter" method="get">
                            <input type="hidden" value="orders" name="tab" class="filter__tab">
                        <table class="filter__table">
                            <tr class="filter__tr">
                                <td class="filter__td">
                                    <td class="filter__td">
                                        <select name="service_type" class="selector">
                                            <option value="default">Service Type</option>
                                            <option value="mto" <?= $service_type == 'mto' ? 'selected' : '' ?>>MTO</option>
                                            <option value="repair" <?= $service_type == 'repair' ? 'selected' : '' ?>>Repair</option>
                                        </select>
                                    </td>
                                    <td class="filter__td">
                                        <select name="order_phase" class="selector">
                                            <option value="default">All Phases</option>
                                            <option value="pending_downpayment" <?= $order_phase == 'pending_downpayment' ? 'selected' : '' ?>>Pending Downpayment</option>
                                            <option value="awaiting_furniture" <?= $order_phase == 'awaiting_furniture' ? 'selected' : '' ?>>Awaiting Furniture Pickup</option>
                                            <option value="in_production" <?= $order_phase == 'in_production' ? 'selected' : '' ?>>In Production</option>
                                            <option value="pending_fullpayment" <?= $order_phase == 'pending_fullpayment' ? 'selected' : '' ?>>Pending Fullpayment</option>
                                            <option value="out_for_delivery" <?= $order_phase == 'out_for_delivery' ? 'selected' : '' ?>>Out For Delivery</option>
                                            <option value="received" <?= $order_phase == 'received' ? 'selected' : '' ?>>Received</option>
                                            <option value="cancelled" <?= $order_phase == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="filter__td">
                                        <input class="submit-filter" type="submit" value="Filter">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <table class="onq__table">
                    <!-- Table headers of all orders -->
                    <thead class="onq__thead">
                        <tr class="onq__tr   onq__tr--th">
                            <th class="onq__th">Order ID</th>
                            <th class="onq__th">Item Type</th>
                            <th class="onq__th">Service Type</th>
                            <th class="onq__th">Order Status</th>
                            <th class="onq__th">View</th>
                        </tr>
                    </thead>
                    <tbody class="onq__tbody">
                        <?php if ($orders) : ?>
                            <?php foreach ($orders as $row) : ?>
                                <tr class="onq__tr">
                                    <td class="onq__td"> <?= htmlspecialchars($row["order_id"]) ?> </td>
                                    <td class="onq__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture"] ?? 'N/A'))) ?> </td>
                                    <td class="onq__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?> </td>
                                    <td class="onq__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["order_phase"] ?? 'N/A'))) ?> </td>
                                    <td class="onq__td">
                                        <a href="orders.php?order_id=<?= htmlspecialchars($row["order_id"]) ?>">
                                            >
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination"> <!-- Not working -->
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <?php
                    // Check if $_GET['page'] is set and matches the loop iteration
                    $active_class = (isset($_GET['page']) && $page == $_GET['page']) ? 'active' : '';
                    // Add the active class if it's the first page and $_GET['page'] is not set
                    if (!isset($_GET['page']) && $page === 1) {
                        $active_class = 'active';
                    }
                    ?>
                    <button onclick="window.location.href='orders_and_quotes.php?page=<?php echo $page; ?>&service_type=<?php echo $service_type; ?>&status=<?php echo $status; ?>'" class="pagination__button <?php echo $active_class; ?>"><?php echo $page; ?></button>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>

<script>
    let lastTab = "quotes";
    function openTab(tabName, elmnt) {
        // Hide all tab content
        var tabcontent = document.getElementsByClassName("onq__content");
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
        if (tabName === "tab--quotes") {
            lastTab = "quotes";
        } else {
            lastTab = "orders";
        }

        // Add the "active" class to the clicked tab
        elmnt.classList.add("active");

        // Reset pagination by clearing all URL parameters
        var url = new URL(window.location.href);
        url.search = `?page=1&tab=${lastTab}`; // Clear all parameters
        window.history.replaceState({}, '', url);
        window.location.reload();
    }
</script>
<script src="/js/globals.js"></script>

</html>