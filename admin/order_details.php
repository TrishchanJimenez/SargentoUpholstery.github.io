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
        SELECT *
        FROM (
            SELECT *
            FROM orders
            WHERE order_id = :order_id) AS O
        JOIN quotes Q USING(quote_id)
        LEFT JOIN quote_customs QC ON Q.custom_id = QC.custom_id
        JOIN order_date OD USING(order_id)
        JOIN users U USING(user_id)
        JOIN payment P USING(order_id)
        LEFT JOIN ( 
            SELECT
                address_id,
                address AS delivery_address
            FROM addresses
        ) AS A ON O.del_address_id = A.address_id
        LEFT JOIN(
            SELECT
                order_id,
                pickup_method,
                address AS pickup_address
            FROM pickup P
            JOIN addresses A ON P.pickup_address_id = A.address_id
            ) AS pickup USING(order_id)
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($order);
    if($order['is_cancelled'] === 1) {
        $prod_status = "cancelled";
        $prod_status_text = "Cancelled";
    }
    else {
        $prod_status = str_replace("_", "-", $order['order_status']);
        $prod_status_text = ucwords(str_replace("-", " ", $prod_status));
    } 

    $payment_status = str_replace("_", "-", $order['payment_status']);
    $payment_status_text = ucwords(str_replace("_", " ", $order['payment_status'])); 
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
                        <div class="info-order-detail">
                    </p>
                        <div class="info">
                            <span class="info-name"> ORDER ID </span>
                            <span class="info-detail">
                                <?= $order['order_id'] ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> ORDER TYPE </span>
                            <span class="info-detail">
                                <?php
                                    if($order['service_type'] === "mto") echo "MTO";
                                    else echo "Repair"
                                ?>
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
                                <?= date('M d, Y', strtotime($order['placement_date']))?>
                            </span>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> EST. DELIVERY DATE </span>
                            <span class="info-detail">
                                <?php
                                    if($order['est_completion_date'] === '0000-00-00')  {
                                        echo "N/A";
                                    } else {
                                        echo date('M d, Y', strtotime($order['est_completion_date']));
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> QUOTED PRICE </span>
                            <span class="info-detail">
                                <?php
                                    if(is_null($order['quoted_price'])) echo "N/A";
                                    else echo "₱" . $order['quoted_price'];
                                ?>
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
                            <span class="info-name"> NOTE </span>
                            <span class="info-detail">
                                <?= $order['description'] ?>
                            </span>
                        </div>
                        <?php if(!is_null($order['ref_img_path'])): ?>
                            <div class="info">
                                <span class="info-name">PICTURE</span>
                                <span class="info-detail">
                                    <img src='/<?= $order['ref_img_path'] ?>' alt='' class='repair-img'>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                    if(!is_null($order['downpayment_method'])) {
                        $method = ucfirst($order['downpayment_method']);
                        $verification_status = ucfirst(str_replace("_", " ", $order['downpayment_verification_status']));
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
                                            <img src='{$order['downpayment_img']}' alt=''>
                                        </span>
                                    </div>
                                    {$verification_buttons}
                                </div>
                            </div>
                        ";
                    }
                ?>
                <?php if(!is_null($order['fullpayment_method'])) : ?>
                    <div class="payment-information fullpayment-info">
                        <p class="info-title">
                            FULLPAYMENT INFORMATION
                        </p>
                        <div class="info-order-detail payment-info">
                            <div class="info">
                                <span class="info-name">METHOD</span>
                                <span class="info-detail">
                                    <?= $order["fullpayment_method"] ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name">VERIFICATION STATUS</span>
                                <span class="info-detail">
                                    <?= $order["fullpayment_verification_status"]  ?>
                                </span>
                            </div>
                            <div class="info">
                                <span class="info-name">PROOF OF PAYMENT</span>
                                <span class="info-detail">
                                    <img src="<?= $order["fullpayment_img"] ?>" alt="">
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
                        <div class="info">
                            <span class="info-name">DELIVERY ADDRESS</span>
                            <span class="info-detail"><?= $order['delivery_address'] ?></span>
                        </div>
                        <?php if(!is_null($order['pickup_address'])): ?>
                            <div class="info">
                                <span class="info-name">PICKUP ADDRESS</span>
                                <span class="info-detail"><?= $order['pickup_address'] ?></span>
                            </div>
                        <?php endif; ?>                             
                    </div>
                </div>
                <?php if($order['order_status'] === "pending_downpayment") : ?>
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
                                <div class="on-accept action-input">
                                    <label for="price">Price for the Order</label>
                                    <input type="number" name="price" class="price-input" placeholder="₱12131" required>
                                </div>
                                <div class="on-click">
                                    <input type="submit" value="save" class="green-button action-button">
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
    if(isset($_POST['is_accepted']) && $_SERVER['REQUEST_METHOD'] === "POST") {
        $order_id = $_POST['order_id'];
        $is_accepted = $_POST['is_accepted'] === 'true' ? true : false;

        if($is_accepted) {
            $price = $_POST['price'];
            $stmt = $conn->prepare("UPDATE orders SET is_accepted = 'accepted', order_status = 'pending_downpayment', quoted_price = :price WHERE order_id = :order_id");
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            createNotif($order['user_id'], "Your order has been accepted", "/my/user_order_details.php?order-id=" . $order['order_id']);
        } else {
            $rejection_reason = $_POST['rejection-reason'];
            $stmt = $conn->prepare("UPDATE orders SET is_accepted = 'rejected', refusal_reason = :reason WHERE order_id = :order_id");
            $stmt->bindParam(':reason', $rejection_reason);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            createNotif($order['user_id'], "Your order has unfortunately been rejected. Click to see Reason", "/my/user_order_details.php?order-id={$order_id}");
        }
        // header("Location: ".$_SERVER['PHP_SELF']);
        // exit();
    }
?>