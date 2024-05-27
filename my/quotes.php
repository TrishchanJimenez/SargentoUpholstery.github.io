<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];

    if (!isset($_GET['quote_id'])) {
        header('Location: orders_and_quotes.php');
        exit;
    }

    $quote_id = htmlspecialchars($_GET['quote_id']);

    require_once('../database_connection.php');

    $query = "
        SELECT 
            * 
        FROM 
            `quotes` 
                LEFT JOIN
            `quote_customs`
                USING (custom_id)
        WHERE 
            `quote_id` = :quote_id 
                AND 
            `customer_id` = :customer_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
    $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quote) {
        echo "Quote not found or you do not have permission to view this quote.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/quotes.css">
    <title>Quote Details - Sargento Upholstery</title>
</head>

<body>
    <?php require_once('../header.php'); ?>
    <div class="quotes__wrapper">
        <a href="orders_and_quotes.php" class="quotes__back-button">< Back to Orders and Quotes</a>
        <div class="quotes"> 
            <div class="quote-details__wrapper">
                <div class="quote-details">
                    <table class="quote-details__table">
                        <h1 class="quote-details__title">Quote Details</h1>
                        <tr>
                            <th class="quote-details__th">Furniture Type</th>
                            <th class="quote-details__th">Service Type</th>
                        </tr>
                        <tr>
                            <td class="quote-details__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($quote["furniture_type"] ?? 'N/A'))) ?> </td>
                            <td class="quote-details__td"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($quote["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?> </td>
                        </tr>
                        <tr>
                            <th class="quote-details__th">Quantity</th>
                            <th class="quote-details__th">Current Status</th>
                        </tr>
                        <tr>
                            <td class="quote-details__td"> <?= html_entity_decode($quote["quantity"])?> </td>
                            <td class="quote-details__td"> <?= ucwords(str_replace('_', ' ', html_entity_decode($quote["quote_status"])))?> </td>
                        </tr>
                        <tr>
                            <th class="quote-details__th">Description</th>
                            <th class="quote-details__th">Reference Image</th>
                        </tr>
                        <tr>
                            <td class="quote-details__td"> <?= html_entity_decode($quote["description"])?> </td>
                            <td class="quote-details__td"> <img class="quote-details__ref-img" src="<?= html_entity_decode($quote["ref_img_path"])?>"> </td>
                        </tr>
                    </table>
                    <table class="quote-details__table quote-details__table--customs">
                        <div class="quote-details__title">
                            <h1>Quote Customization</h1>
                        </div>
                        <tr>
                            <th class="quote-details__th">Dimensions</th>
                            <th class="quote-details__th">Materials</th>
                        </tr>
                        <tr>
                            <td class="quote-details__td"> <?= ucwords(html_entity_decode($quote["dimensions"] ?? 'None')) ?> </td>
                            <td class="quote-details__td"> <?= ucwords(html_entity_decode($quote["materials"] ?? 'None')) ?> </td>
                        </tr>
                        <tr>
                            <th class="quote-details__th">Fabric</th>
                            <th class="quote-details__th">Color</th>
                        </tr>
                        <tr>
                            <td class="quote-details__td"> <?= ucwords(html_entity_decode($quote["fabric"] ?? 'None')) ?> </td>
                            <td class="quote-details__td"> <?= ucwords(html_entity_decode($quote["color"] ?? 'None')) ?> </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="quotes__right-wrapper">
                <div class="quote-price__wrapper">
                    <table class="quote-details__table quote-details__table--price">
                        <div class="quote-details__title">
                            <h1>Quoted Price</h1>
                        </div>
                        <tr>
                            <td class="quote-details__td quote-details__td--price"><?= "Php " . number_format($quote["quoted_price"] ?? 0, 2, '.', ','); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="quote-actions__wrapper">
                    <div class="quote-actions__title">
                        <h1>Quote Actions</h1>
                    </div>
                    <div class="quote-actions">
                        <?php
                            if ($quote['quote_status'] != "cancelled" && $quote['quote_status'] != "accepted") {
                                echo '<button class="quote-actions__cancel">Cancel Order</button>';
                            }
                            if ($quote['quote_status'] == "approved") {
                                echo '<button class="quote-actions__accept">Accept Order</button>';
                            } 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const quoteId = <?= $quote_id ?>;
    </script>
    <script src="/js/my/quotes.js">
    </script>
</body>
</html>
