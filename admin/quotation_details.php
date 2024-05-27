<?php
    require '../database_connection.php';
    session_start();
    if(!isset($_SESSION['access_type']) || $_SESSION['access_type'] !== "admin") {
        header("Location: ../index.php");
        exit();
    }

    if(!isset($_GET['quote-id'])) {
        header("Location: ./orders.php");
        exit();
    }
    
    $quote_id = $_GET['quote-id'];
    $sql = "
        SELECT 
            * 
        FROM 
            quotes Q
        LEFT JOIN
            quote_customs QC USING(custom_id) 
        JOIN
            users U ON Q.customer_id = U.user_id
        WHERE 
            Q.quote_id = :quote_id
        ORDER BY 
            Q.created_at DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quote_id', $quote_id);
    $stmt->execute();
    $quote_details = $stmt->fetch(PDO::FETCH_ASSOC);

    $orders = null;
    if ($quote_details['furniture_type'] === 'multiple') {
        $sql = "
            SELECT 
                * 
            FROM 
                multis
            LEFT JOIN
                quote_customs USING(custom_id) 
            WHERE 
                quote_id = :quote_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':quote_id', $quote_id);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin/quotations.css">
</head>
<body class="orders">
    <?php require 'sidebar.php' ?>
    <div class="order-detail">
        <p class="main-title">Quote Detail</p>
        <hr class="divider">
        <div class="breadcrumb">
            <a href="quotations.php">Quote </a>
            <span> / <?= $quote_details['quote_id'] ?></span>
        </div>
        <div class="detail">
            <div class="left">
                <div class="order-information">
                    <p class="info-title">
                        QUOTE INFORMATION   
                    </p>
                    <div class="info-order-detail">
                        <div class="info">
                            <span class="info-name"> QUOTE ID </span>
                            <span class="info-detail">
                                <?= $quote_details['quote_id'] ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> SERVICE TYPE </span>
                            <span class="info-detail">
                                <?= $quote_details['service_type'] === "mto" ? "MTO" : "Repair" ?>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> FURNITURE TYPE </span>
                            <span class="info-detail">
                                <?= ucfirst($quote_details['furniture_type']) ?>
                            </span>
                        </div>
                        <?php if($quote_details['furniture_type'] !== 'multiple'): ?>
                            <div class="info">
                                <span class="info-name"> QUANTITY </span>
                                <span class="info-detail">
                                    <?= $quote_details['quantity'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="info">
                            <span class="info-name"> QUOTE PLACEMENT DATE </span>
                            <span class="info-detail">
                                <?= date('M d, Y', strtotime($quote_details['created_at']))?>
                            </span>
                            </span>
                        </div>
                        <div class="info">
                            <span class="info-name"> QUOTE STATUS </span>
                            <span class="info-detail status">
                                <span <?= "data-prod-status='{$quote_details['quote_status']}'" ?> >
                                    <?= ucfirst($quote_details['quote_status']) ?>
                                </span>
                            </span>
                        </div>
                        <?php if(!is_null($quote_details['quoted_price']) && $quote_details['quoted_price'] !== ''): ?>
                            <div class="info">
                                <span class="info-name">QUOTED PRICE</span>
                                <span class="info-detail">
                                    <?= '₱' . number_format($quote_details['quoted_price'], 2, '.', ',') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if($quote_details['furniture_type'] !== 'multiple'): ?>
                            <div class="info">
                                <span class="info-name"> NOTE </span>
                                <span class="info-detail">
                                    <?= $quote_details['description'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if(!is_null($quote_details['dimensions']) && $quote_details['dimensions'] !== '' && $quote_details['furniture_type'] !== 'multiple'): ?>
                            <div class="info">
                                <span class="info-name">DIMENSIONS</span>
                                <span class="info-detail">
                                    <?= $quote_details['dimensions'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if(!is_null($quote_details['materials']) && $quote_details['materials'] !== '' && $quote_details['furniture_type'] !== 'multiple'): ?>
                            <div class="info">
                                <span class="info-name">MATERIALS</span>
                                <span class="info-detail">
                                    <?= $quote_details['materials'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if(!is_null($quote_details['fabric']) && $quote_details['fabric'] !== '' && $quote_details['furniture_type'] !== 'multiple'): ?>
                            <div class="info">
                                <span class="info-name">FABRIC</span>
                                <span class="info-detail">
                                    <?= $quote_details['fabric'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if(!is_null($quote_details['color']) && $quote_details['color'] !== '' && $quote_details['furniture_type'] !== 'multiple'): ?>
                            <div class="info">
                                <span class="info-name">COLOR</span>
                                <span class="info-detail">
                                    <?= $quote_details['color'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if(!is_null($quote_details['ref_img_path'])): ?>
                            <div class="info">
                                <span class="info-name">PICTURE</span>
                                <span class="info-detail">
                                    <img src='/<?= $quote_details['ref_img_path'] ?>' alt='' class='repair-img'>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($quote_details['furniture_type'] === 'multiple'): ?>
                    <?php $counter = 0; foreach($orders as $order): $counter++; ?> 
                        <div class="order-information">
                            <p class="info-title">FURNITURE <?= $counter ?></p>   
                            <div class="info-order-detail"> 
                                <div class="info">
                                    <span class="info-name"> TYPE </span>
                                    <span class="info-detail">
                                        <?= ucfirst($order['furniture_type']) ?>
                                    </span>
                                </div>
                                <div class="info">
                                    <span class="info-name"> QUANTITY </span>
                                    <span class="info-detail">
                                        <?= $order['quantity'] ?>
                                    </span>
                                </div>
                                <div class="info">
                                    <span class="info-name"> NOTE </span>
                                    <span class="info-detail">
                                        <?= $order['description'] ?>
                                    </span>
                                </div>
                                <?php if(!is_null($order['dimensions']) && $order['dimensions'] !== ''): ?>
                                    <div class="info">
                                        <span class="info-name">DIMENSIONS</span>
                                        <span class="info-detail">
                                            <?= $order['dimensions'] ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if(!is_null($order['materials']) && $order['materials'] !== ''): ?>
                                    <div class="info">
                                        <span class="info-name">MATERIALS</span>
                                        <span class="info-detail">
                                            <?= $order['materials'] ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if(!is_null($order['fabric']) && $order['fabric'] !== ''): ?>
                                    <div class="info">
                                        <span class="info-name">FABRIC</span>
                                        <span class="info-detail">
                                            <?= $order['fabric'] ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if(!is_null($order['color']) && $order['color'] !== ''): ?>
                                    <div class="info">
                                        <span class="info-name">COLOR</span>
                                        <span class="info-detail">
                                            <?= $order['color'] ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if(!is_null($order['ref_img_path']) && $order['ref_img_path'] !== ''): ?>
                                    <div class="info">
                                        <span class="info-name">PICTURE</span>
                                        <span class="info-detail">
                                            <img src='/<?= $order['ref_img_path'] ?>' alt='' class='repair-img'>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                            <span class="info-detail"><?= $quote_details['name'] ?></span>
                        </div>
                        <div class="info">
                            <span class="info-name">EMAIL</span>
                            <span class="info-detail"><?= $quote_details['email'] ?></span>
                        </div>
                        <div class="info">
                            <span class="info-name">CONTACT NO.</span>
                            <span class="info-detail"><?= $quote_details['contact_number'] ?></span>
                        </div>
                    </div>
                </div>
                <?php if($quote_details['quote_status'] === "pending") : ?>
                    <div class='order-action'>
                        <p class="info-title">
                            ACTIONS
                        </p>
                        <div class="info-order-detail">
                            <div class="action-buttons">
                                <input type="button" value="quote" class="green-button accept-order action-button">
                            </div>
                            <form action="" method="post" id="order-accept-form">
                                <input type="hidden" name="quote_id" <?= "value={$quote_id}" ?>>
                                <div class="on-accept action-input">
                                    <label for="price"> QUOTED PRICE</label>
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
    <script>let quoteId = <?= $quote_details['quote_id'] ?></script>
    <script src="/js/quotation.js"></script>
</body>
</html>
<?php
    include_once("../notif.php");
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        $quote_id = $_POST['quote_id'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("UPDATE quotes SET quote_status = 'approved', quoted_price = :price WHERE quote_id = :quote_id");
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quote_id', $quote_id);
        $stmt->execute();
        createNotif($quote_details['customer_id'], "Your request for quotation has been evaluated", "/my/user_order_details.php?quote-id=" . $quote_details['quote_id']);
        // header("Location: ".$_SERVER['PHP_SELF']);
        // exit();
    }
?>