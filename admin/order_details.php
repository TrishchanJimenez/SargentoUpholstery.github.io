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
    $stmt = $conn->query("SELECT order_type FROM orders WHERE order_id = '$order_id'");
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($order);
    $order_type = $order['order_type'];

    $query = "
        SELECT *
        FROM (
        SELECT *
        FROM orders
        WHERE order_id = $order_id) AS O
        JOIN $order_type USING(order_id)
        JOIN order_date USING(order_id)
        JOIN users USING(user_id)
        JOIN payment USING(order_id)
    ";

    $stmt = $conn->query($query);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($order);
    $prod_status = str_replace("_", "-", $order['order_status']);
    $prod_status_text = ucwords(str_replace("-", " ", $prod_status));

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
    <link rel="stylesheet" href="../css/admin-orders.css">
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
                                <?php
                                    if($order['order_type'] === "mto") echo "MTO";
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
                                <span <?= "data-payment='{$payment_status}'" ?>>
                                    <?= $payment_status_text ?>
                                </span>
                                <select name='select-payment-status' id=''>
                                    <option value='unpaid'>Unpaid</option>
                                    <option value='partially-paid'>Partially Paid</option>
                                    <option value='fully-paid'>Fully Paid</option>
                                </select>
                            </span>
                        </div>
                        <?php
                            if($order['order_type'] === "mto") {
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
                                        {$order['height']} inches(H) x {$order['width']} inches(W) x {$order['depth']} inches(D)
                                    </span>
                                </div>"
                                ;
                            }
                        ?>
                        <div class="info">
                            <span class="info-name"> NOTE </span>
                            <span class="info-detail">
                                <?= $order['notes'] ?>
                            </span>
                        </div>
                        <?php
                            if($order['order_type'] === "repair") {
                                echo " 
                                <div class='info'>
                                    <span class='info-name'>
                                        PICTURE
                                    </span>
                                    <span class='info-detail'>
                                        <img src='/{$order['repair_img_path']}' alt='' class='repair-img'>
                                    </span>
                                </div>"
                                ;
                            }
                        ?>
                    </div>
                </div>
                <div class="payment-information">
                    
                </div>
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
                            <span class="info-detail">Blk 123 Lt 21 Brgy. Ninonin, Taguig City</span>
                        </div>
                    </div>
                </div>
                <?php
                    if($order['is_accepted'] === "pending") {
                        echo "<div class='order-action is-new-order'>";
                    } else {
                        echo "<div class='order-action'>";
                    }
                ?>
                    <p class="info-title">
                        ACTIONS
                    </p>
                    <div class="info-order-detail">
                        <div class="action-buttons">
                            <input type="button" value="accept order" class="green-button accept-order action-button">
                            <input type="button" value="reject order" class="red-button reject-order action-button">
                        </div>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" <?= "value={$order_id}" ?>>
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
            </div>
        </div>
    </div>
    <script src="/js/order-detail.js"></script>
</body>
</html>
<?php
    if(isset($_POST['order_id']) && $_SERVER['REQUEST_METHOD'] === "POST") {
        $is_accepted = false;
        $price = 0;
        $rejection_reason = "";
        if(isset($_POST['price'])) {
            $is_accepted = true; 
            $price = $_POST['price'];
        } else {
            $rejection_reason = $_POST['rejection-reason'];
        }
        $order_id = $_POST['order_id'];

        if($is_accepted) {
            $stmt = $conn->prepare("UPDATE orders SET is_accepted = 'accepted', order_status = 'pending_downpayment', quoted_price = :price WHERE order_id = :order_id");
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE orders SET is_accepted = 'rejected', refusal_reason = :reason WHERE order_id = :order_id");
            $stmt->bindParam(':reason', $rejection_reason);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
        }
        echo "
            <script>
                location.reload();
            <script>
        ";
    }
?>
