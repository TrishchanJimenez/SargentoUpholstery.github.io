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
                            <span class="info-name"> EST. DELIVERY DATE </span>
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
                            <span class="info-name"> ORDER STATUS </span>
                            <span class="info-detail status">
                                <span <?= "data-prod-status='{$prod_status}'" ?> >
                                    <?= $prod_status_text ?>
                                </span>
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
                    </div>
                </div>
                    <div class="multi-order-information order-information hidden">
                        <p class="info-title">
                            FURNITURE LIST   
                        </p>
                        <?php $counter = 0; foreach($items as $item): $counter++; ?> 
                            <div class="order-information">
                                <p class="info-title">FURNITURE <?= $counter ?></p>   
                                <div class="info-order-detail"> 
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
                                        <div class="info">
                                            <span class="info-name">PICTURE</span>
                                            <span class="info-detail">
                                                <img src='/<?= $item['item_ref_img'] ?>' alt='' class='repair-img'>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php
                    if(!is_null($order['downpay_method'])) {
                        $method = ucfirst($order['downpay_method']);
                        $verification_status = ucfirst(str_replace("_", " ", $order['downpayment_verification_status'] ?? ''));
                        $verification_buttons = $order['downpayment_verification_status'] === 'waiting_for_verification' ? "
                            <div class='verification-buttons button-container downpayment'>
                                <input type='button' value='Verify' class='green-button accept-verification'>
                                <input type='button' value='Needs Reverification' class='red-button reject-verification'>
                            </div>" : "";
                        echo "
                            <div class='payment-information downpayment-info'>
                                <p class='info-title'>
                                    DOWNPAYMENT INFORMATION
                                </p>
                                <div class='info-order-detail payment-info'>
                                    <div class='info'>
                                        <span class='info-name'>METHOD</span>
                                        <span class='info-detail'>
                                            {$method}
                                        </span>
                                    </div>
                                    <div class='info'>
                                        <span class='info-name'>VERIFICATION STATUS</span>
                                        <span class='info-detail downpayment-status'>
                                            {$verification_status}
                                        </span>
                                    </div>
                                    <div class='info'>
                                        <span class='info-name'>PROOF OF PAYMENT</span>
                                        <span class='info-detail'>
                                            <img src='/{$order['downpayment_img']}' alt=''>
                                        </span>
                                    </div>
                                    {$verification_buttons}
                                </div>
                            </div>
                        ";
                    }
                ?>
                <?php if(!is_null($order['fullpay_method'])) : ?>
                    <div class="payment-information fullpayment-info">
                        <p class="info-title">
                            FULLPAYMENT INFORMATION
                        </p>
                        <div class="info-order-detail payment-info">
                            <div class="info">
                                <span class="info-name">METHOD</span>
                                <span class="info-detail">
                                    <?= ucfirst($order["fullpay_method"]) ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name">VERIFICATION STATUS</span>
                                <span class="info-detail">
                                    <?= ucfirst(str_replace('_', ' ', $order["fullpayment_verification_status"]))  ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name">PROOF OF PAYMENT</span>
                                <span class="info-detail">
                                    <img src="<?= '/' . $order["fullpayment_img"] ?>" alt="">
                                </span>
                            </div>
                            <?php if($order['fullpayment_verification_status'] === "waiting_for_verification") : ?>
                                <div class="verification-buttons button-container fullpayment">
                                    <input type="button" value="Verify" class="green-button accept-verification">
                                    <input type="button" value="Needs Reverification" class="red-button reject-verification">
                                </div> 
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
                        <?php if(!is_null($order['delivery_address'])): ?>
                            <div class="info">
                                <span class="info-name">PICKUP ADDRESS</span>
                                <span class="info-detail"><?= $order['delivery_address'] ?></span>
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
                            ACTIONS
                        </p>
                        <div class="info-order-detail">
                            <div class="action-buttons">
                                <input type="button" value="reject order" class="red-button reject-order action-button">
                            </div>
                            <form action="" method="post" id="order-accept-form">
                                <input type="hidden" name="order_id" <?= "value={$order_id}" ?>>
                                <input type="hidden" name="is_accepted">
                                <div class="on-reject action-input">
                                    <label for="rejection-reason">Reason for rejection</label>
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
<?php
    include_once("../notif.php");
    if(isset($_POST['reject-order']) && $_SERVER['REQUEST_METHOD'] === "POST") {
        $order_id = $_POST['order_id'];

        $rejection_reason = $_POST['rejection-reason'];
        $stmt = $conn->prepare("UPDATE orders SET is_cancelled = 1, refusal_reason = :reason WHERE order_id = :order_id");
        $stmt->bindParam(':reason', $rejection_reason);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        // createNotif($order['user_id'], "Your order has unfortunately been rejected. Click to see Reason", "/my/user_order_details.php?order-id={$order_id}");
        // header("Location: ".$_SERVER['PHP_SELF']);
        echo "<script>window.location.href = window.location.href</script>";
        // exit();
    }
?>