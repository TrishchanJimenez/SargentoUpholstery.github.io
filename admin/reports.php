<?php
    require '../database_connection.php';
    session_start();
    // var_dump($_SESSION);
    // if(!isset($_SESSION['user_type']) || $_SESSION['access_type'] === "customer"){
    //     // echo "test";
    //     header("Location: ../index.php");
    //     exit();
    // }

    if(isset($_POST))

    $search_type = $_GET['search-order'] ?? '';
    $search_input = $_GET['search-input'] ?? '';
    $order_type = $_GET['order-type'] ?? '';
    $order_prod_status = $_GET['order-prod-status'] ?? '';
    $order_payment_status = $_GET['order-payment-status'] ?? '';
    $order_sort = $_GET['order-sort'] ?? '';
    $current_page = isset($_GET['page']) ? (int)($_GET['page']) : 1;

    $count_query = "
        SELECT COUNT(*) AS total_records
        FROM orders O 
        JOIN quotes USING(quote_id)
        JOIN users U ON O.user_id = U.user_id
        JOIN order_date OD ON OD.order_id = O.order_id
        JOIN payment P ON P.order_id = O.order_id
        WHERE 1
    ";

    $query = "
        SELECT
            O.order_id,
            U.name AS customer_name,
            Q.furniture_type AS item,
            Q.service_type AS order_type,
            Q.quantity,
            Q.quoted_price AS price,
            OD.placement_date,
            O.order_status AS prod_status,
            O.is_cancelled,
            P.payment_status
        FROM orders O 
        JOIN quotes Q USING (quote_id)
        JOIN users U ON O.user_id = U.user_id
        JOIN order_date OD ON OD.order_id = O.order_id
        JOIN payment P ON P.order_id = O.order_id
        WHERE 1
    ";

    if (!empty($search_input)) {
        switch($search_type) {
            case "order_id":
                if (is_numeric($search_input)) {
                    $count_query .= " AND O.order_id = $search_input";
                    $query .= " AND O.order_id = $search_input";
                    break;
                } else {
                    break;
                }
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
    if (!($order_type === 'default' || empty($order_type))) {
        $count_query .= " AND service_type = '$order_type'";
        $query .= " AND service_type = '$order_type'";
    }
    if (!($order_prod_status === 'default' || empty($order_prod_status))) {
        $count_query .= " AND O.order_status = '$order_prod_status'";
        $query .= " AND O.order_status = '$order_prod_status'";
    }
    if (!($order_payment_status === 'default' || empty($order_payment_status))) {
        $count_query .= " AND payment_status = '$order_payment_status'";
        $query .= " AND payment_status = '$order_payment_status'";
    }

    $count_result = $conn->query($count_query);
    $total_records = $count_result->fetch(PDO::FETCH_ASSOC)['total_records'];

    // Append the ORDER BY and LIMIT clauses for fetching the actual records
    if (!($order_sort === 'default' || empty($order_sort))) {
        $query .= " ORDER BY $order_sort DESC";
    } else {
        $query .= " ORDER BY O.last_updated DESC";
    }


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
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .order-list, .order-list * {
                visibility: visible;
            }
            .order-list {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
        }
}
    </style>
</head>
<body>
    <div class="orders" >
            <div class="admin-sidebar">
            <h1>
                <p class="text-gold">Sargento</p>
                <p class="text-gold">Upholstery</p>
            </h1>
            <ul class="admin-nav">
                <a href="./dashboard.php" class="admin-link fill-icon" data-page="dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4"><path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z"/></svg>
                    <svg class="hovered" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="./orders.php" class="admin-link" data-page="orders">
                    <svg class="unhovered" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="shopping-bag-02">
                        <path id="Icon" d="M16.0004 9V6C16.0004 3.79086 14.2095 2 12.0004 2C9.79123 2 8.00037 3.79086 8.00037 6V9M3.59237 10.352L2.99237 16.752C2.82178 18.5717 2.73648 19.4815 3.03842 20.1843C3.30367 20.8016 3.76849 21.3121 4.35839 21.6338C5.0299 22 5.94374 22 7.77142 22H16.2293C18.057 22 18.9708 22 19.6423 21.6338C20.2322 21.3121 20.6971 20.8016 20.9623 20.1843C21.2643 19.4815 21.179 18.5717 21.0084 16.752L20.4084 10.352C20.2643 8.81535 20.1923 8.04704 19.8467 7.46616C19.5424 6.95458 19.0927 6.54511 18.555 6.28984C17.9444 6 17.1727 6 15.6293 6L8.37142 6C6.82806 6 6.05638 6 5.44579 6.28984C4.90803 6.54511 4.45838 6.95458 4.15403 7.46616C3.80846 8.04704 3.73643 8.81534 3.59237 10.352Z" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                    <span>Orders</span>
                </a>
                <a href="./quotations.php" class="admin-link fill-icon" data-page="quotations">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4"><path d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z"/></svg>
                    <span>Quotes</span>
                </a>
                <a href="./cms.php" class="admin-link fill-icon" data-page="cms">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="edit_document_24dp_FILL0_wght400_GRAD0_opsz24 1">
                        <path id="Vector" d="M14 22V18.925L19.525 13.425C19.675 13.275 19.8417 13.1667 20.025 13.1C20.2083 13.0333 20.3917 13 20.575 13C20.775 13 20.9667 13.0375 21.15 13.1125C21.3333 13.1875 21.5 13.3 21.65 13.45L22.575 14.375C22.7083 14.525 22.8125 14.6917 22.8875 14.875C22.9625 15.0583 23 15.2417 23 15.425C23 15.6083 22.9667 15.7958 22.9 15.9875C22.8333 16.1792 22.725 16.35 22.575 16.5L17.075 22H14ZM15.5 20.5H16.45L19.475 17.45L19.025 16.975L18.55 16.525L15.5 19.55V20.5ZM6 22C5.45 22 4.97917 21.8042 4.5875 21.4125C4.19583 21.0208 4 20.55 4 20V4C4 3.45 4.19583 2.97917 4.5875 2.5875C4.97917 2.19583 5.45 2 6 2H14L20 8V11H18V9H13V4H6V20H12V22H6ZM19.025 16.975L18.55 16.525L19.475 17.45L19.025 16.975Z" fill="#C4CAD4"/>
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z"/></svg>
                    <span>Content</span>
                </a>
                <a href="./chat.php" class="admin-link fill-icon" id="chatSystemLink" data-page="chat">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                    <span>Chat</span>
                </a>
                <a href="./reports.php" class="admin-link fill-icon" data-page="reports">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4"><path d="M280-280h80v-200h-80v200Zm320 0h80v-400h-80v400Zm-160 0h80v-120h-80v120Zm0-200h80v-80h-80v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M280-280h80v-200h-80v200Zm320 0h80v-400h-80v400Zm-160 0h80v-120h-80v120Zm0-200h80v-80h-80v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
                    <span>Reports</span>
                </a>
            </ul>
            <ul class="admin-extra">
                <a href="../index.php" class="admin-link fill-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="home_24dp_FILL0_wght400_GRAD0_opsz24 1">
                        <path id="Vector" d="M6 19H9V13H15V19H18V10L12 5.5L6 10V19ZM4 21V9L12 3L20 9V21H13V15H11V21H4Z" fill="#C4CAD4"/>
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
                    <span>Back To Home</span>
                </a>
                <a href="../api/logout.php" class="admin-link">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="Icons">
                        <path id="Icon" d="M11.1956 2.87988C16.4976 2.87988 20.7956 7.17795 20.7956 12.4799C20.7956 17.7818 16.4976 22.0799 11.1956 22.0799M8.79557 16.3199L4.95557 12.4799M4.95557 12.4799L8.79557 8.63988M4.95557 12.4799H16.9556" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                    <span>Logout</span>
                </a>
            </ul>
        </div>
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
            <div class="selected-multiple">
                <img src="/websiteimages/icons/close-icon-gray.svg" alt="" class="close-icon">
                <span class="selected-count"></span><span>selected</span>
                <span class="advance-next">Advance To Next Phase<img src="/websiteimages/icons/arrow-right.svg" alt=""></span>
            </div>
            <table class="order-table" id="Print_area">
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($orders AS $order) {
                            $price = is_null($order['price']) ? "N/A" : "₱{$order['price']}";
                            $date = date('M d, Y', strtotime($order['placement_date']));

                            $prod_status = str_replace("_", "-", $order['prod_status']);
                            $prod_status_text = ucwords(str_replace("-", " ", $prod_status));

                            $payment_status = str_replace("_", "-", $order['payment_status']);
                            $payment_status_text = ucwords(str_replace("_", " ", $order['payment_status'])); 
                            $newOrderOption = $order['prod_status'] === "new_order" ? "<option value='new-order'>New Order</option>" : "";
                            $type = ($order['order_type'] === "mto") ? "MTO" : "Repair";
                            $order_status = str_replace("_", "-", $order['prod_status']);
                            $statuses = [
                                "pending-downpayment" => "Pending Downpayment",
                                "ready-for-pickup" => "Ready for Pickup",
                                "in-production" => "In Production",
                                "pending-fullpayment" => "Pending Fullpayment",
                                "out-for-delivery" => "Out for Delivery",
                                "received" => "Received"
                            ];
                            $prod_status_options = "";
                            $include = false;
                            $is_cancelled = $order['is_cancelled'] === 1 ? true : false;
                            if($is_cancelled) {
                                $prod_status = "cancelled";
                                $prod_status_text = "Cancelled";
                                // $prod_status_options .= "<option value='cancelled'>Cancelled</option>";
                            } 
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
                            $item = ucfirst($order['item']);
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
            <hr class="divider">
            <div class="query-records">
                <div class="record-count">
                    Showing 
                    <span>
                        <?php
                            if($total_records === 0) echo 0;
                            else echo (($current_page-1) * 10) + 1;
                        ?>
                    </span>
                    to 
                    <span>
                    <?php 
                        if($current_page * 10 > $total_records) echo $total_records;
                        else echo $current_page * 10;
                    ?>
                    </span> of <span><?= $total_records ?></span> results
                </div>
            </div>
            <button id="print">Save As PDF</button>
        </div>
    </div>

    
    <script>
        const save_pdf=document.getElementById('print');

        save_pdf.addEventListener('click',function(){
            print()
        })
    </script>

    
    <script src="../js/admin/order.js"></script>
    <script src="../js/sidebar.js"></script>
</body>
</html>