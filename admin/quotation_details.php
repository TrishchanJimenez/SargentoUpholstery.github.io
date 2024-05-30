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

    // PROCESS THE SET PRICE FORM
    include_once("../notif.php");
    include_once("../alert.php");
    if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit-price'])) {
        // echo "helloj";
        $total_price = 0;
        // var_dump($_POST);
        $quote_id = $_POST['quote_id'];
        $item_ids = $_POST['item_id'];
        $prices = $_POST['price'];

        for($i = 0; $i < count($item_ids); $i++) {
            $total_price += $prices[$i];
            $sql = "UPDATE items SET item_price = :price WHERE item_id = :item_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':price', $prices[$i]);
            $stmt->bindParam(':item_id', $item_ids[$i]);
            $stmt->execute();
        }

        $sql = "UPDATE quotes SET total_price = :total_price, quote_status = 'approved' WHERE quote_id = :quote_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->bindParam(':quote_id', $quote_id);
        $stmt->execute();
        // sendAlert('success', 'Quote price has been set successfully');
        // header("Refresh: 0");
    }
?>
<?php
    if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['reject-quote'])) {
        $quote_id = $_POST['quote_id'];
        $rejection_reason = $_POST['rejection-reason'];
        $sql = "UPDATE quotes SET quote_status = 'rejected', rejection_reason = :rejection_reason WHERE quote_id = :quote_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':rejection_reason', $rejection_reason);
        $stmt->bindParam(':quote_id', $quote_id);
        $stmt->execute();
        sendAlert('success', 'Quote has been rejected');
        // header("Refresh: 0");
    }    
?>
<?php
    $quote_id = $_GET['quote-id'];
    $sql = "
        SELECT 
            *
        FROM 
            quotes Q
        LEFT JOIN
            items I USING(quote_id)
        JOIN
            users U ON Q.customer_id = U.user_id
        WHERE 
            Q.quote_id = :quote_id
        ORDER BY
            Q.quote_id DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quote_id', $quote_id);
    $stmt->execute();
    $quote_details = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "
        SELECT 
            * 
        FROM 
            items
        LEFT JOIN
            customs USING(custom_id) 
        WHERE 
            quote_id = :quote_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quote_id', $quote_id);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $quote_type = count($items) > 1 ? 'multiple' : 'Single';
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
                <div class="order-information ">
                    <p class="info-title">
                        QUOTE INFORMATION   
                    </p>
                    <div class="info-order-detail quote-detail-main">
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
                            <span class="info-name"> QUOTE TYPE </span>
                            <span class="info-detail">
                                <?= ucfirst($quote_type) ?>
                            </span>
                        </div>
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
                        <?php if(!is_null($quote_details['total_price']) && $quote_details['total_price'] !== ''): ?>
                            <div class="info">
                                <span class="info-name">TOTAL PRICE</span>
                                <span class="info-detail">
                                    <?= '₱' . number_format($quote_details['total_price'], 2, '.', ',') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if(!is_null($quote_details['rejection_reason']) && $quote_details['rejection_reason'] !== ''): ?>
                            <div class="info">
                                <span class="info-name">REASON FOR REJECTION</span>
                                <span class="info-detail">
                                    <?= $quote_details['rejection_reason'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $counter = 0; foreach($items as $item): $counter++; ?> 
                    <div class="order-information">
                        <p class="info-title">FURNITURE <?= $counter ?></p>   
                        <div class="info-order-detail  quote-detail-item"> 
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
                            <?php if(!is_null($item['item_price'])): ?>
                                <div class="info">
                                    <span class="info-name">ITEM PRICE</span>
                                    <span class="info-detail">
                                        <?= '₱' . number_format($item['item_price'], 2, '.', ',') ?>
                                    </span>
                                </div>
                            <?php endif; ?>
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
                        <!-- <div class="info-order-detail">
                            <div class="action-buttons">
                                <input type="button" value="quote" class="green-button accept-order action-button" onclick="openModal()">
                                <form action="post" id="rejection-form">
                                    <input type="submit" name="reject-quote" value="Reject" class="red-button reject-order action-button">
                                </form>
                            </div>
                        </div> -->
                        <div class="info-order-detail">
                            <div class="action-buttons">
                                <input type="button" value="quote" class="green-button accept-order action-button" onclick="openModal()">
                                <input type="button" value="reject quote" class="red-button reject-order reject-quote action-button">
                            </div>
                            <form action="" method="post" id="quote-reject-form">
                                <input type="hidden" name="quote_id" <?= "value={$quote_id}" ?>>
                                <div class="on-reject action-input">
                                    <label for="rejection-reason">Reason for rejection</label>
                                    <textarea name="rejection-reason" rows="3" placeholder="write reason here..." class="rejection-input" required></textarea>
                                </div>
                                <div class="on-click">
                                    <input type="submit" value="save" name="reject-quote" class="green-button action-button" name="reject-order">
                                    <input type="button" value="cancel" class="red-button action-button">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="quote-modal-background">
        <div class="quote-modal">
            <div class="modal-header">
                <h2 class="modal-title">Set Quote Price</h2>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <form class="modal-body" method="post">
                <input type="hidden" name="quote_id" value="<?= $quote_id ?>">
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Furniture</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>   
                    <?php $counter = 0; foreach($items as $item): $counter++?>
                        <input type="hidden" name="item_id[]" value="<?= $item['item_id'] ?>">
                        <tr>
                            <td class="item-counter"><?= $counter ?></td>
                            <td><?= ucwords($item['furniture']) ?></td>
                            <td><?= 'x'.$item['quantity'] ?></td>
                            <td class="price-input">₱<input type="number" name="price[]" id="" required></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="total">
                    <span class="total-title">Total</span>
                    <span>
                        ₱<span class="total-price">0.00</span>
                    </span>
                </div>
                <div class="action-buttons">
                    <input type="submit" value="Save" class="save-button action-button" name="submit-price">
                    <input type="button" value="Cancel" class="cancel-button action-button" onclick="cancelPriceSet()">
                </div>
            </form>
        </div>
    </div>
    <script src="/js/quotation.js"></script>
</body>
</html>