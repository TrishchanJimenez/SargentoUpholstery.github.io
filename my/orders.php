<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];

    if (!isset($_GET['order_id'])) {
        header('Location: orders_and_quotes.php');
        exit;
    }

    $order_id = htmlspecialchars($_GET['order_id']);

    require_once('../database_connection.php');

    $query = "
        SELECT 
            o.*, 
            q.*,
            p.*,
            a.address
        FROM 
            `orders` o
        INNER JOIN 
            `quotes` q
            ON o.quote_id = q.quote_id
        INNER JOIN 
            `payment` p
            ON p.order_id = :order_id
        LEFT JOIN 
            `addresses` a
            ON o.del_address_id = a.address_id
        WHERE 
            o.order_id = :order_id 
            AND 
            o.user_id = :customer_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Order not found or you do not have permission to view this quote.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/orders.css">
    <link rel="stylesheet" href="/css/set_order_address.css">
    <title>Order Details - Sargento Upholstery</title>
</head>

<body>
    <?php require_once('../header.php'); ?>
    <div class="orders__wrapper">
        <a href="orders_and_quotes.php" class="orders__back-button">< Back to Orders and Quotes</a>
        <div class="orders">
            <div class="orders__details-wrapper">
                <div class="order-details">
                    <table class="orders__table">
                        <h1 class="orders__title">Order Details</h1>
                        <tr>
                            <th class="orders__th">Furniture Type</th>
                            <th class="orders__th">Service Type</th>
                        </tr>
                        <tr>
                            <td class="orders__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["furniture_type"] ?? 'N/A'))) ?> </td>
                            <td class="orders__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?> </td>
                        </tr>
                        <tr>
                            <th class="orders__th">Quantity</th>
                            <th class="orders__th">Current Status</th>
                        </tr>
                        <tr>
                            <td class="orders__td"> <?= html_entity_decode($order["quantity"])?> </td>
                            <td class="orders__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["order_status"])))?> </td>
                        </tr>
                        <?php if($order["service_type"] == "repair"): ?>
                        <tr>
                            <th class="orders__th">Pickup Method</th>
                            <th class="orders__th">Pickup Address</th>
                        </tr>
                        <tr>
                            <td class="orders__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["pickup_method"] ?? 'N/A'))) ?> </td>
                            <td class="orders__td"> <?= ucwords(html_entity_decode($order["pickup_address"] ?? 'N/A')) ?> </td>
                        </tr>
                        <?php endif ?>
                        <tr>
                            <th class="orders__th">Delivery Method</th>
                            <th class="orders__th">Delivery Address</th>
                        </tr>
                        <tr>
                            <td class="orders__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["del_method"] ?? 'N/A'))) ?> </td>
                            <td class="orders__td"> <?= ucwords(html_entity_decode($order["del_address"] ?? 'N/A')) ?> </td>
                        </tr>
                        <tr>
                            <th class="orders__th">Description</th>
                            <th class="orders__th">Reference Image</th>
                        </tr>
                        <tr>
                            <td class="orders__td"> <?= html_entity_decode($order["description"])?> </td>
                            <td class="orders__td"> <img class="order-details__ref-img" src="<?= html_entity_decode($order["ref_img_path"])?>"> </td>
                        </tr>
                        <tr>
                            <th class="orders__th">Downpayment</th>
                            <th class="orders__th">Fullpayment</th>
                        </tr>
                        <tr>
                            <td class="orders__td"> <img class="order-details__ref-img" src="<?= html_entity_decode($order["downpayment_img"] ?? "None")?>"> </td>
                            <td class="orders__td"> <img class="order-details__ref-img" src="<?= html_entity_decode($order["fullpayment_img"] ?? "None")?>"> </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="orders__right-wrapper">
                <div class="order-price__wrapper">
                    <table class="orders__table orders__table--price">
                        <div class="orders__title">
                            <h1>Quoted Price</h1>
                        </div>
                        <tr>
                            <td class="orders__td orders__td--price"><?= "Php " . number_format($order["quoted_price"] ?? 0, 2, '.', ','); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="order-actions__wrapper">
                    <table class="orders__table orders__table--actions">
                        <div class="orders__title">
                            <h1>Order Actions</h1>
                        </div>  
                        <tr>
                            <?php
                                switch ($order['order_status']) {
                                    case "pending_downpayment":
                                        $enablePickup = ($order['service_type'] == "repair") ? true : false;
                                        echo '<td>';
                                        include('set_order_address.php');
                                        echo '</td></tr>';
                                        echo '<tr><td>';
                                        include('upload_proof_of_downpayment.php');
                                        echo '</td>';
                                        break;
                                    case "pending_fullpayment":
                                        echo '<td>';
                                        include('upload_proof_of_fullpayment.php');
                                        echo '</td></tr>';
                                        break;
                                    case "out_for_delivery":
                                        echo '<button class="order-actions__button">Confirm Delivery of Order</button>';
                                        break;
                                    case "received":
                                        echo '<button class="order-actions__button">Review Order</button>';
                                        break;
                                    default:
                                        echo 'No actions currently available.';
                                        break;
                                }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>