<?php
    require '../database_connection.php';
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
    }

    $search_type = isset($_GET['search-order']) ? $_GET['search-order'] : 'default';
    $search_input = isset($_GET['search-input']) ? $_GET['search-input'] : '';
    $order_type = isset($_GET['order-type']) ? $_GET['order-type'] : 'default';
    $order_prod_status = isset($_GET['order-prod-status']) ? $_GET['order-prod-status'] : 'default';
    $order_payment_status = isset($_GET['order-payment-status']) ? $_GET['order-payment-status'] : 'default';
    $order_sort = isset($_GET['order-sort']) ? $_GET['order-sort'] : 'default';
    $current_page = 1;
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
        $current_page = $_GET['previous_page'];

        if($page === "previous") $current_page--;
        else if($page === "next") $current_page++;
        else $current_page = (int)$page;
    }

    $count_query = "
    SELECT COUNT(*) AS total_records
    FROM orders O 
    JOIN users U ON O.user_id = U.user_id
    JOIN order_date OD ON OD.order_id = O.order_id
    JOIN payment P ON P.order_id = O.order_id
    WHERE 1
    ";

    $query = "
        SELECT
            O.order_id,
            U.name AS customer_name,
            O.furniture_type AS item,
            O.order_type,
            O.quoted_price AS price,
            OD.placement_date,
            O.order_status AS prod_status,
            P.payment_status
        FROM orders O 
        JOIN users U ON O.user_id = U.user_id
        JOIN order_date OD ON OD.order_id = O.order_id
        JOIN payment P ON P.order_id = O.order_id
        WHERE 1
    ";

    if (!empty($search_input)) {
        switch($search_type) {
            case "order_id":
                $count_query .= " AND O.order_id = $search_input";
                $query .= " AND O.order_id = $search_input";
                break;
            case "item":
                $count_query .= " AND O.furniture_type LIKE '%$search_input%'";
                $query .= " AND item LIKE '%$search_input%'";
                break;
            case "customer_name":
                $count_query .= " AND U.name LIKE '%$search_input%'";
                $query .= " AND customer_name LIKE '%$search_input%'";
                break;
        }
    }
    if ($order_type !== 'default') {
        $count_query .= " AND order_type = '$order_type'";
        $query .= " AND order_type = '$order_type'";
    }
    if ($order_prod_status !== 'default') {
        $count_query .= " AND O.order_status = '$order_prod_status'";
        $query .= " AND prod_status = '$order_prod_status'";
    }
    if ($order_payment_status !== 'default') {
        $count_query .= " AND payment_status = '$order_payment_status'";
        $query .= " AND payment_status = '$order_payment_status'";
    }

    $count_result = $conn->query($count_query);
    $total_records = $count_result->fetch(PDO::FETCH_ASSOC)['total_records'];

    // Append the ORDER BY and LIMIT clauses for fetching the actual records
    if ($order_sort !== 'default') {
        $query .= " ORDER BY $order_sort";
    }

    $query .= " LIMIT 10";
    if($current_page !== 1) {
        $query .= " OFFSET " . (($current_page - 1) * 10);
    }

    $stmt = $conn->query($query);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $page_count = $total_records < 10 ? 1 : ceil($total_records / 10);

    if($current_page > $page_count) {
        $current_page = $page_count;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin-orders.css">
</head>
<body>
    <div class="orders">
        <?php require 'sidebar.php' ?>
        <div class="order-list">
            <p class="main-title">Order</p>
            <hr class="divider">
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
                    <select name="order-type" id="" class="selector">
                        <option value="default">Type</option>
                        <option value="mto">MTO</option>
                        <option value="repair">Repair</option>
                    </select>
                </div>
                <div class="filter-prod-status selector-container">
                    <select name="order-prod-status" id="" class="selector">
                        <option value="default">Prod. Status</option>
                        <option value="new_order">New Order</option>
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
                <input type="submit" value="Filter">
            </form>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Customer Name</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Placement Date</th>
                        <th>Prod. Status</th>
                        <th>Payment Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($orders AS $order) {
                            $price = is_null($order['price']) ? "N/A" : "₱{$order['price']}";
                            $date = date('M d, Y', strtotime($order['placement_date']));

                            $prod_status = str_replace("_", "-", $order['prod_status']);
                            if($prod_status === "pending-first-installment") $prod_status = "pending-downpayment";
                            if($prod_status === "pending-second-installment") $prod_status = "pending-fullpayment";
                            $prod_status_text = ucwords(str_replace("-", " ", $prod_status));

                            $payment_status = str_replace("_", "-", $order['payment_status']);
                            $payment_status_text = ucwords(str_replace("_", " ", $order['payment_status'])); 
                            $pickupOption = $order_type === "repair" ? "<option value='ready-for-pickup'>Ready For Pickup</option>" : ""; 
                            echo "
                            <tr data-id='{$order['order_id']}'>
                                <td>{$order['order_id']}</td>
                                <td>{$order['customer_name']}</td>
                                <td>{$order['item']}</td>
                                <td>{$order['order_type']}</td>
                                <td>{$price}</td>
                                <td>{$date}</td>
                                <td class='prod-status status'>
                                    <span data-prod-status='{$prod_status}'>{$prod_status_text}</span>
                                    <select name='select-prod-status' id=''>
                                        <option value='pending-downpayment'>Pending Downpayment</option>
                                        <option value='ready-for-pickup'>Ready For Pickup</option>
                                        {$pickupOption}
                                        <option value='pending-fullpayment'>Pending Fullpayment</option>
                                        <option value='out-for-delivery'>Out For Delivery</option>
                                        <option value='received'>Received</option>
                                    </select>
                                </td>
                                <td class='payment-status status'>
                                    <span data-payment='{$payment_status}'>{$payment_status_text}</span>
                                    <select name='select-payment-status' id=''>
                                        <option value='unpaid'>Unpaid</option>
                                        <option value='partially-paid'>Partially Paid</option>
                                        <option value='fully-paid'>Fully Paid</option>
                                    </select>
                                </td>
                                <td class='chevron-right'>
                                    <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#6B7280'><path d='M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z'/></svg>
                                </td>
                            </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
            <hr class="divider">
            <div class="query-records">
                <div class="record-count">
                    Showing <span><?= (($current_page-1) * 10) + 1?></span> to <span><?php 
                        if($current_page * 10 > $total_records) echo $total_records;
                        else echo $current_page * 10;
                    ?></span> of <span><?= $total_records ?></span> results
                </div>
                <form class="pagination" method="get">
                    <input type="hidden" name="previous_page" <?php echo "value='{$current_page}'"?>>
                    <button type="submit" name="page" value="previous" class="previous-page page-btn" <?php if($current_page === 1) echo "disabled"?>>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7071 5.29289C13.0976 5.68342 13.0976 6.31658 12.7071 6.70711L9.41421 10L12.7071 13.2929C13.0976 13.6834 13.0976 14.3166 12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289L11.2929 5.29289C11.6834 4.90237 12.3166 4.90237 12.7071 5.29289Z" fill="#6B7280"/>
                        </svg>
                    </button>
                    <?php
                        for ($i=1; $i <= $page_count; $i++) { 
                            $is_active_page = $current_page === $i ? 'active-page' : '';
                            $disabled = $current_page === $i ? 'disabled' : '';
                            echo "
                                <button type='submit' name='page' value='{$i}' class='page-btn {$is_active_page}' {$disabled}>
                                    {$i}
                                </button>
                            ";
                        }
                    ?>
                    <button type="submit" value="next" name="page" class="next-page page-btn" <?php if($current_page === $page_count) echo "disabled"?>>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 14.7071C6.90237 14.3166 6.90237 13.6834 7.29289 13.2929L10.5858 10L7.29289 6.70711C6.90237 6.31658 6.90237 5.68342 7.29289 5.29289C7.68342 4.90237 8.31658 4.90237 8.70711 5.29289L12.7071 9.29289C13.0976 9.68342 13.0976 10.3166 12.7071 10.7071L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071Z" fill="#6B7280"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/admin-order.js"></script>
</body>
</html>