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
            `quotes` q
                RIGHT JOIN
            `items` i ON q.quote_id = i.quote_id
                LEFT JOIN
            `customs` c ON i.item_id = c.item_id
        WHERE
            q.quote_id = :quote_id
                AND
            q.customer_id = :customer_id
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
    <link rel="stylesheet" href="/css/quotes-new.css">
    <title>Quote Details - Sargento Upholstery</title>
</head>

<body>
    <?php require_once('../header.php'); ?>
    <div class="quotes__wrapper">
        <a href="orders_and_quotes.php" class="quotes__back-button">< Back to Orders and Quotes</a>
        <div class="quotes"> 
            <div class="quotes__top">
                <div class="quote-general__wrapper">
                    <div class="quote-type__wrapper">
                        <div class="quote-type__title   quote-section__title">
                            <h1>Service Type</h1>
                        </div>
                        <table>
                            <tr>
                                <td><?= ucwords(str_replace('_', ' ', htmlspecialchars($quote["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="quote-status__wrapper">
                        <div class="quote-status__title   quote-section__title">
                            <h1>Current Status</h1>
                        </div>
                        <table>
                            <tr>
                                <td><?= ucwords(str_replace('_', ' ', html_entity_decode($quote["quote_status"]))) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="quote-price__wrapper">
                    <div class="quote-price__title   quote-section__title">
                        <h1>Total Price</h1>
                    </div>
                    <table>
                        <tr>
                            <td><?= number_format($quote["total_price"] ?? 0, 2, '.', ',') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="quote-actions__wrapper">
                    <div class="quote-actions__title   quote-section__title">
                        <h1>Quote Actions</h1>
                    </div>
                    <table>
                        <tr>
                            <?php if($quote['quote_status'] != "cancelled" && $quote['quote_status'] != "accepted"): ?>
                                <td>
                                    <button class="quote-actions__cancel">Cancel Order</button>
                                </td>
                            <?php endif; ?>
                            <?php if($quote['quote_status'] == "approved"): ?>
                                <td>
                                    <button class="quote-actions__accept">Accept Order</button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="quotes__bottom">
                <div class="quote-details__wrapper">
                    <div class="quote-details__title quote-section__title">
                        <h1>Quote Details</h1>
                    </div>
                    <?php
                        $query = "SELECT * FROM `items` WHERE `quote_id` = :quote_id";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
                        $stmt->execute();

                        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <table class="quote-details">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Furniture Type</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($stmt->rowCount() > 0): ?>
                                <?php foreach ($items as $i => $item): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= ucwords(htmlspecialchars($item["furniture"] ?? 'N/A')) ?></td>
                                        <td><?= ucwords(htmlspecialchars($item["description"] ?? 'N/A')) ?></td>
                                        <td><?= htmlspecialchars($item["quantity"] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($item["item_price"] ?? 'N/A') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
