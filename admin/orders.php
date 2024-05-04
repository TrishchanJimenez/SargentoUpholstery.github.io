<?php
    require '../database_connection.php';
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
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
                        <option value="orderID">Order ID</option>
                        <option value="custname">Cust. Name</option>
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
                        <option value="pending-dp">Pending Downpayment</option>
                        <option value="pickup-ready">Ready for Pickup</option>
                        <option value="in-production">In Production</option>
                        <option value="pending-full">Pending Fullpayment</option>
                        <option value="delivering">Delivering</option>
                        <option value="received">Received</option>
                    </select>
                </div>
                <div class="filter-payment-status selector-container">
                    <select name="order-payment-status" id="" class="selector">
                        <option value="default">Payment</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partially-paid">Partially Paid</option>
                        <option value="fully-paid">Fully Paid</option>
                    </select>
                </div>
                <div class="filter-sort selector-container">
                    <select name="order-sort" id="" class="selector">
                        <option value="default">Sort By</option>
                        <option value="order-id">Order ID</option>
                        <option value="est-delivery">Est. Delivery Date</option>
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
                    <tr>
                        <td>59217</td>
                        <td>Cody Fisher</td>
                        <td>Sofa</td>
                        <td>Repair</td>
                        <td>₱20,102.31</td>
                        <td>Mar 12, 2024</td>
                        <td class="prod-status status">
                            <span data-prod-status="new-order">New Order</span>
                            <select name="select-prod-status" id="">
                                <option value="pending-downpayment">Pending Downpayment</option>
                                <option value="ready-for-pickup">Ready For Pickup</option>
                                <option value="in-production">In Production</option>
                                <option value="pending-fullpayment">Pending Fullpayment</option>
                                <option value="out-for-delivery">Out For Delivery</option>
                                <option value="received">Received</option>
                            </select>
                        </td>
                        <td class="payment-status status">
                            <span data-payment="fully-paid">Fully Paid</span>
                            <select name="select-payment-status" id="">
                                <option value="unpaid">Unpaid</option>
                                <option value="partially-paid">Partially Paid</option>
                                <option value="fully-paid">Fully Paid</option>
                            </select>
                        </td>
                        <td class="chevron-right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td>59217</td>
                        <td>Cody Fisher</td>
                        <td>Sofa</td>
                        <td>Repair</td>
                        <td>₱20,102.31</td>
                        <td>Mar 12, 2024</td>
                        <td class="prod-status status">
                            <span data-prod-status="new-order">New Order</span>
                            <select name="select-prod-status" id="">
                                <option value="pending-downpayment">Pending Downpayment</option>
                                <option value="ready-for-pickup">Ready For Pickup</option>
                                <option value="in-production">In Production</option>
                                <option value="pending-fullpayment">Pending Fullpayment</option>
                                <option value="out-for-delivery">Out For Delivery</option>
                                <option value="received">Received</option>
                            </select>
                        </td>
                        <td class="payment-status status">
                            <span data-payment="fully-paid">Fully Paid</span>
                            <select name="select-payment-status" id="">
                                <option value="unpaid">Unpaid</option>
                                <option value="partially-paid">Partially Paid</option>
                                <option value="fully-paid">Fully Paid</option>
                            </select>
                        </td>
                        <td class="chevron-right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td>59217</td>
                        <td>Cody Fisher</td>
                        <td>Sofa</td>
                        <td>Repair</td>
                        <td>₱20,102.31</td>
                        <td>Mar 12, 2024</td>
                        <td class="prod-status status">
                            <span data-prod-status="new-order">New Order</span>
                            <select name="select-prod-status" id="">
                                <option value="pending-downpayment">Pending Downpayment</option>
                                <option value="ready-for-pickup">Ready For Pickup</option>
                                <option value="in-production">In Production</option>
                                <option value="pending-fullpayment">Pending Fullpayment</option>
                                <option value="out-for-delivery">Out For Delivery</option>
                                <option value="received">Received</option>
                            </select>
                        </td>
                        <td class="payment-status status">
                            <span data-payment="fully-paid">Fully Paid</span>
                            <select name="select-payment-status" id="">
                                <option value="unpaid">Unpaid</option>
                                <option value="partially-paid">Partially Paid</option>
                                <option value="fully-paid">Fully Paid</option>
                            </select>
                        </td>
                        <td class="chevron-right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr class="divider">
            <div class="query-records">
                <div class="record-count">
                    Showing <span>1</span> to <span>3</span> of <span>3</span> results
                </div>
                <div class="pagination">

                </div>
            </div>
        </div>
    </div>
    <script src="../js/admin-order.js"></script>
</body>
</html>