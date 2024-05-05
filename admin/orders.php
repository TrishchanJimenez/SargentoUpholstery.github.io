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
                $query .= " AND O.order_id = $search_input";
                break;
            case "item":
                $query .= " AND item LIKE '%$search_input%'";
                break;
            case "customer_name":
                $query .= " AND customer_name LIKE '%$search_input%'";
                break;
        }
    }
    if ($order_type !== 'default') {
        $query .= " AND order_type = '$order_type'";
    }
    if ($order_prod_status !== 'default') {
        $query .= " AND prod_status = '$order_prod_status'";
    }
    if ($order_payment_status !== 'default') {
        $query .= " AND payment_status = '$order_payment_status'";
    }
    if ($order_sort !== 'default') {
        $query .= " ORDER BY $order_sort";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $order_count = $stmt->rowCount();
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
                        <option value="pending_first_installment">Pending Downpayment</option>
                        <option value="ready_for_pickup">Ready for Pickup</option>
                        <option value="in_production">In Production</option>
                        <option value="pending_second_installment">Pending Fullpayment</option>
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
                    Showing <span>1</span> to <span><?= $order_count ?></span> of <span><?= $order_count ?></span> results
                </div>
                <form class="pagination">

                </form>
            </div>
        </div>
    </div>
    <script src="../js/admin-order.js"></script>
</body>
</html>