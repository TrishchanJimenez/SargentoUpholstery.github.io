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
            a.address
        FROM 
            `orders` o
        INNER JOIN 
            `quotes` q
            ON o.quote_id = q.quote_id
        INNER JOIN 
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
    <link rel="stylesheet" href="/css/orders_and_quotes.css">
    <title>Order Details - Sargento Upholstery</title>
</head>

<body>
    <?php require_once('../header.php'); ?>
    <div class="orders">
        <div class="order-details__wrapper">
            <div class="order-details">
                <table class="order-details__table">
                    <h1 class="order-details__title">Order Details</h1>
                    <tr>
                        <th class="order-details__th">Furniture Type</th>
                        <th class="order-details__th">Service Type</th>
                    </tr>
                    <tr>
                        <td class="order-details__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["furniture_type"] ?? 'N/A'))) ?> </td>
                        <td class="order-details__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?> </td>
                    </tr>
                    <tr>
                        <th class="order-details__th">Quantity</th>
                        <th class="order-details__th">Current Status</th>
                    </tr>
                    <tr>
                        <td class="order-details__td"> <?= html_entity_decode($order["quantity"])?> </td>
                        <td class="order-details__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["order_status"])))?> </td>
                    </tr>
                    <?php if($order["service_type"] == "repair"): ?>
                    <tr>
                        <th class="order-details__th">Pickup Method</th>
                        <th class="order-details__th">Pickup Address</th>
                    </tr>
                    <tr>
                        <td class="order-details__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["pickup_method"] ?? 'N/A'))) ?> </td>
                        <td class="order-details__td"> <?= ucwords(html_entity_decode($order["pickup_address"] ?? 'N/A')) ?> </td>
                    </tr>
                    <?php endif ?>
                    <tr>
                        <th class="order-details__th">Delivery Method</th>
                        <th class="order-details__th">Delivery Address</th>
                    </tr>
                    <tr>
                        <td class="order-details__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["del_method"] ?? 'N/A'))) ?> </td>
                        <td class="order-details__td"> <?= ucwords(html_entity_decode($order["del_address"] ?? 'N/A')) ?> </td>
                    </tr>
                    <tr>
                        <th class="order-details__th" colspan="2">Quoted Price</th>
                    </tr>
                    <tr>
                        <td class="order-details__td" colspan="2"> <?= ucwords(str_replace('_', ' ', html_entity_decode($order["quoted_price"] ?? 'N/A'))) ?> </td>
                    </tr>
                    <tr>
                        <th class="order-details__th">Description</th>
                        <th class="order-details__th">Reference Image</th>
                    </tr>
                    <tr>
                        <td class="order-details__td"> <?= html_entity_decode($order["description"])?> </td>
                        <td class="order-details__td"> <img class="order-details__ref-img" src="<?= html_entity_decode($order["ref_img_path"])?>"> </td>
                    </tr>
                </table>
            </div>
            <a href="orders_and_quotes.php" class="order-details__back-button">Back to Orders and Quotes</a>
        </div>
        <div class="order-actions__wrapper">
            <div class="order-actions__title">
                <h1>Order Actions</h1>
            </div>
            <div class="order-actions">
                <?php
                    switch ($order['order_status']) {
                        case "pending_downpayment":
                            $enablePickup = ($order['service_type'] == "repair") ? true : false;
                            include('order_address_form.php');
                            echo '<button class="order-actions__button">Upload Proof of Downpayment</button>';
                            break;
                        case "pending_fullpayment":
                            echo '<button class="order-actions__button">Upload Proof of Fullpayment</button>';
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
            </div>
        </div>
    </div>
</body>
</html>