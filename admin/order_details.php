<?php
    require '../database_connection.php';
    include_once("../notif.php");
    include_once("../alert.php");
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
    }

    if(!isset($_GET['order-id'])) {
        header("Location: ./orders.php");
        exit();
    }

    // HANDLING OF ADMIN ORDER REJECTION
    if(isset($_POST['reject-order']) && $_SERVER['REQUEST_METHOD'] === "POST") {
        $order_id = $_POST['order_id'];
        $user_id = $_POST['user_id'];

        $rejection_reason = $_POST['rejection-reason'];
        $stmt = $conn->prepare("UPDATE orders SET order_phase = 'cancelled', cancellation_reason = :reason WHERE order_id = :order_id");
        $stmt->bindParam(':reason', $rejection_reason);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        createNotif($user_id, "Your order has unfortunately been rejected. Click here to see Reason", "/my/orders.php?order_id={$order_id}");
        sendAlert("success", "Order has succesfully been rejected");
        // header("Refresh: 0");
        // exit();
    }

    // HANDLING OF PAYMENT VERIFICATION
    if(isset($_POST['order_type']) && $_SERVER['REQUEST_METHOD'] === "POST") {
        $order_id = $_POST['order_id'];
        $user_id = $_POST['user_id'];
        $order_type = $_POST['order_type'];
        
        if(isset($_POST['verify-downpayment'])) {
            // Update downpayment verification status to verified
            $query = "UPDATE downpayment SET downpay_verification_status = 'verified' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            
            $query = "UPDATE orders SET order_phase = :order_phase, payment_phase = 'partially_paid' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            // CHECK THE TYPE OF THE ORDER

            $order_phase = '';
            if($order_type === "mto") {
                $order_phase = 'in_production';
            } else {
                $order_phase = 'awaiting_furniture';
            }
            $stmt->bindParam(':order_phase', $order_phase);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                // Update successful
                createNotif($user_id, "Your order is now in production", "/my/orders.php?id=$order_id");
                if($order_type === "mto") {
                    sendAlert("success", "Downpayment has been successfully verified. Order is now in production.");
                } else {
                    sendAlert("success", "Downpayment has been successfully verified. Now waiting for the furnitures to repair");
                }
            }  
        }
        else if(isset($_POST['reverify-downpayment'])) {
            // Update downpayment verification status to needs_reverification
            $query = "UPDATE downpayment SET downpay_verification_status = 'needs_reverification', downpay_img_path = NULL WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                // echo json_encode(["payment_status" => 'Needs Reverification']);
                createNotif($user_id, "Your downpayment needs reverification", "/my/orders.php?id=$order_id");
                sendAlert("success", "Downpayment status has been updated to needs reverification");
            } else {
                // Update failed
                // echo "Failed to update downpayment verification status";
            }
        }
        else if(isset($_POST['verify-fullpayment'])) {
            // Update fullpayment status verification status to verified
            $query = "UPDATE fullpayment SET fullpay_verification_status = 'verified' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            $query = "UPDATE orders SET order_phase = 'out_for_delivery', payment_phase = 'fully_paid' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                // echo json_encode(["payment_status" => 'Verified']);
                sendAlert("success", "Fullpayment has been successfully verified. Order is now set for delivery.");
            } else {
                // Update failed
                // echo "Failed to update downpayment verification status";
            }
        } 
        else if(isset($_POST['reverify-fullpayment'])) {
            // Update downpayment verification status to needs_reverification
            $query = "UPDATE fullpayment SET fullpay_verification_status = 'needs_reverification', fullpay_img_path = NULL WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                // echo "Downpayment verification status updated to needs_reverification";
                sendAlert("success", "Downpayment status has been updated to needs reverification");
            } else {
                // Update failed
                // echo "Failed to update downpayment verification status";
            }
        }
    }
    
    $order_id = $_GET['order-id'];

    $query = "
        SELECT 
            *,
            O.created_at AS placement_date
        FROM (
            SELECT *
            FROM orders
            WHERE order_id = :order_id) AS O
        JOIN quotes Q USING(quote_id)
        JOIN users U ON U.user_id = Q.customer_id
        LEFT JOIN
            downpayment USING(order_id)
        LEFT JOIN
            fullpayment USING(order_id)
        LEFT JOIN
            items I USING(quote_id) 
        LEFT JOIN
            customs C USING(custom_id)  
        LEFT JOIN
            delivery D USING(order_id)
        LEFT JOIN
            pickup P USING(order_id)
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($order);
    $prod_status = str_replace("_", "-", $order['order_phase']);
    $prod_status_text = ucwords(str_replace("-", " ", $prod_status));

    $payment_status = str_replace("_", "-", $order['payment_phase']);
    $payment_status_text = ucwords(str_replace("_", " ", $order['payment_phase'])); 

    $items = null;
    $sql = "
        SELECT 
            I.*,
            C.* 
        FROM 
            quotes Q
        LEFT JOIN
            items I USING(quote_id)
        LEFT JOIN
            customs C USING(custom_id)
        WHERE 
            quote_id = :quote_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quote_id', $order['quote_id']);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin/orders.css">
</head>
<body class="orders">
    <?php require 'sidebar.php' ?>
    <div class="order-detail">
        <p class="main-title">Order Detail</p>
        <hr class="divider">
        <div class="breadcrumb">
            <a href="orders.php">Order </a>
            <span> / <?= $order['order_id'] ?></span>
        </div>
        <div class="detail">
            <div class="left">
                <div class="order-information">
                    <p class="info-title">
                        ORDER INFORMATION   
                    </p>
                    <div class="info-order-detail order-detail-main">
                        <div class="info">
                            <span class="info-name"> ORDER ID </span>
                            <span class="info-detail">
                                <?= $order['order_id'] ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> ORDER TYPE </span>
                            <span class="info-detail">
                                <?=
                                    $order['service_type'] === "mto" ? "Made-To-Order" : "Repair"
                                ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> ORDER PLACEMENT DATE </span>
                            <span class="info-detail">
                                <?= date('M d, Y', strtotime($order['placement_date']))?>
                            </span>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> EST. COMPLETION DATE </span>
                            <span class="info-detail">
                                <?= date('M d, Y', strtotime($order['est_completion_date'])); ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> TOTAL PRICE </span>
                            <span class="info-detail">
                                <?= is_null($order['total_price']) ? "N/A" : "₱" . $order['total_price'] ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> REMAINING BALANCE </span>
                            <span class="info-detail">
                                <?php
                                    if($order['payment_phase'] === "unpaid") {
                                        echo "₱" . $order['total_price'];
                                    } else if($order['payment_phase'] === "partially_paid") {
                                        echo "₱" . $order['total_price'] - $order['downpay_amount'];
                                    } else {
                                        echo "N/A";
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> ORDER STATUS </span>
                            <span class="info-detail status prod-status">
                                <?php
                                    $order_status = str_replace("_", "-", $order['order_phase']);
                                    $statuses = [
                                        "pending-downpayment" => "Pending Downpayment",
                                        "awaiting-furniture" => "Awaiting Furniture",
                                        "in-production" => "In Production",
                                        "pending-fullpayment" => "Pending Fullpayment",
                                        "out-for-delivery" => "Out for Delivery",
                                        "received" => "Received",
                                    ];
                                    $prod_status_options = "";

                                    $include = false;
                                    foreach ($statuses as $status => $status_text) {
                                        if ($status === "awaiting-furniture" && $order['service_type'] === "mto") {
                                            continue;
                                        }
                                        if ($include) {
                                            $prod_status_options .= "<option value='{$status}'>{$status_text}</option>";
                                        }
                                        if ($status === $order_status) {
                                            $prod_status_options .= "<option value='{$status}'>{$status_text}</option>";
                                            $include = true;
                                        }
                                    }
                                ?>
                                <span <?= "data-prod-status='{$prod_status}'" ?> >
                                    <?= $prod_status_text ?>
                                </span>
                                <select name='select-prod-status' id=''>
                                    <?= $prod_status_options ?>
                                </select>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> PAYMENT STATUS </span>
                            <span class="info-detail status">
                                <span <?= "data-payment='{$payment_status}'" ?> class="payment-status">
                                    <?= $payment_status_text ?>
                                </span>
                                <select name='select-payment-status' id=''>
                                    <option value='unpaid'>Unpaid</option>
                                    <option value='partially-paid'>Partially Paid</option>
                                    <option value='fully-paid'>Fully Paid</option>
                                </select>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> FURNITURE DETAILS </span>
                            <span class="info-detail">
                                <button class="toggle-furniture-display">SHOW FURNITURE DETAILS</button>
                            </span>
                        </div>
                        <?php if(!is_null($order['rejection_reason']) && $order['rejection_reason'] !== ''): ?>
                            <div class="info">
                                <span class="info-name"> REJECTION REASON </span>
                                <span class="info-detail">
                                    <?= $order['rejection_reason'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $counter = 0; foreach($items as $item): $counter++; ?> 
                    <div class="order-information item-detail hidden">
                        <p class="info-title">FURNITURE <?= $counter ?></p>   
                        <div class="info-order-detail item-detail-sub"> 
                            <div class="info">
                                <span class="info-name"> TYPE </span>
                                <span class="info-detail">
                                    <?= ucfirst($item['furniture']) ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name"> QUANTITY </span>
                                <span class="info-detail">
                                    <?= $item['quantity'] ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name"> ITEM PRICE </span>
                                <span class="info-detail">
                                    <?= '₱ ' . number_format($item['item_price']) ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name"> NOTE </span>
                                <span class="info-detail">
                                    <?= $item['description'] ?>
                                </span>
                            </div>
                            <?php if(!is_null($item['dimensions']) && $item['dimensions'] !== ''): ?>
                                <div class="info">
                                    <span class="info-name">DIMENSIONS</span>
                                    <span class="info-detail">
                                        <?= $item['dimensions'] ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if(!is_null($item['materials']) && $item['materials'] !== ''): ?>
                                <div class="info">
                                    <span class="info-name">MATERIALS</span>
                                    <span class="info-detail">
                                        <?= $item['materials'] ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if(!is_null($item['fabric']) && $item['fabric'] !== ''): ?>
                                <div class="info">
                                    <span class="info-name">FABRIC</span>
                                    <span class="info-detail">
                                        <?= $item['fabric'] ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if(!is_null($item['color']) && $item['color'] !== ''): ?>
                                <div class="info">
                                    <span class="info-name">COLOR</span>
                                    <span class="info-detail">
                                        <?= $item['color'] ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if(!is_null($item['item_ref_img']) && $item['item_ref_img'] !== ''): ?>
                                <div class="info picture-detail">
                                    <span class="info-name">PICTURE</span>
                                    <span class="info-detail">
                                        <img src='/<?= $item['item_ref_img'] ?>' alt='' class='repair-img'>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(!is_null($order['downpay_method'])) : ?>
                    <div class='payment-information downpayment-info'>
                        <p class='info-title'>
                            DOWNPAYMENT INFORMATION
                        </p>
                        <div class='info-order-detail payment-info'>
                            <div class='info'>
                                <span class='info-name'>MODE OF PAYMENT</span>
                                <span class='info-detail'>
                                    <?= ucfirst($order['downpay_method']) ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>ACCOUNT NAME</span>
                                <span class='info-detail'>
                                    <?= $order['downpay_account_name'] ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>AMOUNT</span>
                                <span class='info-detail'>
                                    <?= '₱ ' . number_format($order['downpay_amount']) ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>REFERENCE NO.</span>
                                <span class='info-detail'>
                                    <?= $order['downpay_ref_no'] ?>
                                </span>
                            </div>
                            <?php if(!is_null($order['downpay_img_path'])) : ?>
                                <div class='info'>
                                    <span class='info-name'>PROOF OF PAYMENT</span>
                                    <span class='info-detail'>
                                        <img src="<?= '/' . $order['downpay_img_path'] ?>" alt="">
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class='info'>
                                <span class='info-name'>VERIFICATION STATUS</span>
                                <span class='info-detail downpayment-status'>
                                    <?= ucfirst(str_replace("_", " ", $order['downpay_verification_status'] ?? '')) ?>
                                </span>
                            </div>
                            <?php if($order['downpay_verification_status'] === "waiting_for_verification") : ?>
                                <form class="verification-buttons button-container downpayment" method="post">
                                    <input type="hidden" name="user_id" value="<?= $order['customer_id'] ?>">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <input type="hidden" name="order_type" value="<?= $order['service_type'] ?>">
                                    <input type='submit' name="verify-downpayment" value='Verify' class='green-button accept-verification'>
                                    <input type='submit' name="reverify-downpayment" value='Needs Reverification' class='red-button reject-verification'>
                                </form> 
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(!is_null($order['fullpay_method'])) : ?>
                    <div class="payment-information fullpayment-info">
                        <p class="info-title">
                            FULLPAYMENT INFORMATION
                        </p>
                        <div class="info-order-detail payment-info">
                            <div class='info'>
                                <span class='info-name'>MODE OF PAYMENT</span>
                                <span class='info-detail'>
                                    <?= ucfirst($order['fullpay_method']) ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>ACCOUNT NAME</span>
                                <span class='info-detail'>
                                    <?= $order['fullpay_account_name'] ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>AMOUNT</span>
                                <span class='info-detail'>
                                    <?= '₱' . number_format($order['fullpay_amount']) ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>REFERENCE NO.</span>
                                <span class='info-detail'>
                                    <?= $order['fullpay_ref_no'] ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>VERIFICATION STATUS</span>
                                <span class='info-detail downpayment-status'>
                                    <?= ucfirst(str_replace("_", " ", $order['fullpay_verification_status'] ?? '')) ?>
                                </span>
                            </div>
                            <div class='info'>
                                <span class='info-name'>PROOF OF PAYMENT</span>
                                <span class='info-detail'>
                                    <img src="<?= '/' . $order['fullpay_img_path'] ?>" alt="">
                                </span>
                            </div>
                            <?php if($order['fullpay_verification_status'] === "waiting_for_verification") : ?>
                                <form class="verification-buttons button-container fullpayment" method="post">
                                    <input type="hidden" name="user_id" value="<?= $order['customer_id'] ?>">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <input type="hidden" name="order_type" value="<?= $order['service_type'] ?>">
                                    <input type="submit" name="verify-fullpayment" value="Verify" class="green-submit accept-verification">
                                    <input type="submit" name="reverify-fullpayment" value="Needs Reverification" class="red-button reject-verification">
                                </form> 
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="right">
                <div class="customer-information">
                    <p class="info-title">
                        CUSTOMER INFORMATION
                    </p>
                    <div class="info-order-detail customer-info">
                        <div class="info">
                            <span class="info-name">CUSTOMER NAME</span>
                            <span class="info-detail"><?= $order['name'] ?></span>
                        </div>
                        <div class="info">
                            <span class="info-name">EMAIL</span>
                            <span class="info-detail"><?= $order['email'] ?></span>
                        </div>
                        <div class="info">
                            <span class="info-name">CONTACT NO.</span>
                            <span class="info-detail"><?= $order['contact_number'] ?></span>
                        </div>
                        <?php if(!is_null($order['delivery_method'])): ?>
                            <div class="info">
                                <span class="info-name">DELIVERY METHOD</span>
                                <span class="info-detail"><?= $order['delivery_method'] ?></span>
                            </div>
                        <?php endif; ?>                             
                        <?php if(!is_null($order['delivery_address'])): ?>
                            <div class="info">
                                <span class="info-name">DELIVERY ADDRESS</span>
                                <span class="info-detail"><?= $order['delivery_address'] ?></span>
                            </div>
                        <?php endif; ?>                             
                        <?php if(!is_null($order['pickup_method'])): ?>
                            <div class="info">
                                <span class="info-name">PICKUP METHOD</span>
                                <span class="info-detail"><?= $order['pickup_method'] ?></span>
                            </div>
                        <?php endif; ?>                             
                        <?php if(!is_null($order['pickup_address'])): ?>
                            <div class="info">
                                <span class="info-name">PICKUP ADDRESS</span>
                                <span class="info-detail"><?= $order['pickup_address'] ?></span>
                            </div>
                        <?php endif; ?>                             
                    </div>
                </div>
                <?php if($order['order_phase'] === "pending_downpayment") : ?>
                    <div class='order-action'>
                        <p class="info-title">
                            ORDER ACTIONS
                        </p>
                        <div class="info-order-detail">
                            <div class="action-buttons">
                                <input type="button" value="Cancel Order" class="red-button reject-order action-button">
                            </div>
                            <form action="" method="post" id="order-accept-form">
                                <input type="hidden" name="order_id" <?= "value={$order_id}" ?>>
                                <input type="hidden" name="user_id" <?= "value={$order['customer_id']}" ?>>
                                <div class="on-reject action-input">
                                    <label for="rejection-reason">Reason for cancellation</label>
                                    <textarea name="rejection-reason" rows="3" placeholder="write reason here..." class="rejection-input" required></textarea>
                                </div>
                                <div class="on-click">
                                    <input type="submit" value="save" class="green-button action-button" name="reject-order">
                                    <input type="button" value="cancel" class="red-button action-button">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>let orderId = <?= $order['order_id'] ?></script>
    <script src="/js/order-detail.js"></script>
</body>
</html>