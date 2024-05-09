<?php
    require '../database_connection.php';
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
    }

    if(!isset($_GET['order-id'])) {
        header("Location: ./orders.php");
        exit();
    }
    
    $order_id = $_GET['order-id'];
    $stmt = $conn->query("SELECT order_type AS type FROM orders WHERE order_id = '$order_id'");
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    $query = "
        SELECT *
        FROM orders
        JOIN $order_type USING(order_id)
        WHERE order_id = $order_id
    ";

    $stmt = $conn->query($query);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin-orders.css">
</head>
<body class="orders">
    <?php require 'sidebar.php' ?>
    <div class="order-detail">
        <p class="main-title">Order Detail</p>
        <hr class="divider">
        <div class="breadcrumb">
            <a href="orders.php">Order </a>
            <span> / 5</span>
        </div>
        <div class="detail">
            <div class="order-information">
                <p class="info-title">
                    ORDER INFORMATION
                </p>
                <div class="info-order-detail">
                    <div class="info">
                        <span class="info-name"> ORDER ID </span>
                        <span class="info-detail">
                            <?= $order['order_id'] ?>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> ORDER TYPE </span>
                        <span class="info-detail">
                            <?= $order['order_type'] ?>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> FURNITURE TYPE </span>
                        <span class="info-detail">
                            <?= $order['furniture_type'] ?>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> ORDER PLACEMENT DATE </span>
                        <span class="info-detail">
                            <?= $order['placement_date'] ?>
                        </span>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> EST. DELIVERY DATE </span>
                        <span class="info-detail">
                            <?= $order['est_completion_date'] ?>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> QUOTE PRICE </span>
                        <span class="info-detail">
                            <?= $order['quoted_price'] ?>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> ORDER STATUS </span>
                        <span class="info-detail status">
                            <span data-prod-status='ready-for-pickup'>Ready For Pickup</span>
                            <select name='select-prod-status' id=''>
                                <option value='pending-downpayment'>Pending Downpayment</option>
                                <option value='ready-for-pickup'>Ready For Pickup</option>
                                <option value='pending-fullpayment'>Pending Fullpayment</option>
                                <option value='out-for-delivery'>Out For Delivery</option>
                                <option value='received'>Received</option>
                            </select>
                        </span>
                    </div>
                    <div class="info">
                        <span class="info-name"> PAYMENT STATUS </span>
                        <span class="info-detail status">
                            <span data-payment='unpaid'>Unpaid</span>
                            <select name='select-payment-status' id=''>
                                <option value='unpaid'>Unpaid</option>
                                <option value='partially-paid'>Partially Paid</option>
                                <option value='fully-paid'>Fully Paid</option>
                            </select>
                        </span>
                    </div>
                    <?php
                        if($order['order_type']) {
                            echo " 
                            <div class='info'>
                                <span class='info-name'>
                                    MATERIALS
                                </span>
                                <span class='info-detail'>
                                    {$order['material']}
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>DIMENSIONS</span>
                                <span class='info-detail'>
                                    {$order['height']} x {$order['width']} x {$order['depth']} 
                                </span>
                            </div>"
                            ;
                        }
                    ?>
                    <div class="info">
                        <span class="info-name"> NOTE </span>
                        <span class="info-detail">
                            <?= $order['note'] ?>
                        </span>
                    </div>
                    <?php
                        if($order['order_type']) {
                            echo " 
                            <div class='info'>
                                <span class='info-name'>
                                    PICTURE
                                </span>
                                <span class='info-detail'>
                                    <img src='{$order['repair_img_path']}' alt=''>
                                </span>
                            </div>"
                            ;
                        }
                    ?>
                </div>
            </div>
            <div class="customer-information">
                <p class="info-title">
                    CUSTOMER INFORMATION
                </p>
                <div class="info-order-detail customer-info">
                    <div class="info">
                        <span class="info-name">CUSTOMER NAME</span>
                        <span class="info-detail">Cameron Williams</span>
                    </div>
                    <div class="info">
                        <span class="info-name">EMAIL</span>
                        <span class="info-detail">cwilliamnson@gmail.com</span>
                    </div>
                    <div class="info">
                        <span class="info-name">CONTACT NO.</span>
                        <span class="info-detail">+63189912331</span>
                    </div>
                    <div class="info">
                        <span class="info-name">DELIVERY ADDRESS</span>
                        <span class="info-detail">Blk 123 Lt 21 Brgy. Ninonin, Taguig City</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>