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
    <link rel="stylesheet" href="/css/quotes.css">
    <title>Quote Details - Sargento Upholstery</title>
</head>

<body>
    <?php require_once('../header.php'); ?>
    <div class="quotes__wrapper">
        <a href="orders_and_quotes.php" class="quotes__back-button">< Back to Orders and Quotes</a>
        <div class="quotes"> 
            <div class="quotes-top   quotes-half">
                <div class="quotes-top__intro   quotes-half__intro">
                    <h1 class="quotes-top__title   quotes-half__title">Quote Information</h1>
                    <p class="quotes-top__desc   quotes-half__desc">
                        This is the detailed information about the quote you submitted. 
                        It includes all the necessary details, actions, and a comprehensive 
                        list of items included in the quote. You can review the information 
                        here to ensure accuracy.
                    </p>
                </div>
                <div class="quote-identif">
                    <div class="quote-number__wrapper   quote-identif__section">
                        <div class="quote-number__title   quote-section__title">
                            <h1>Quote Number: </h1>
                        </div>
                        <table class="quote-number">
                            <tr>
                                <td class="td--top"><?= ($quote["quote_id"]) ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php
                        $status_class = '';
                        switch ($quote["quote_status"]) {
                            case 'pending':
                                $status_class = 'status-pending';
                                break;
                            case 'approved':
                                $status_class = 'status-approved';
                                break;
                            case 'accepted':
                                $status_class = 'status-accepted';
                                break;
                            case 'rejected':
                            case 'cancelled':
                                $status_class = 'status-rejected';
                                break;
                            default:
                                $status_class = 'status-default';
                                break;
                        }
                    ?>
                    <div class="quote-status__wrapper   quote-identif__section   <?= $status_class ?>">
                        <div class="quote-status__title quote-section__title">
                            <h1>Current Status: </h1>
                        </div>
                        <table class="quote-status">
                            <tr>
                                <td class="td--top"><?= ucwords(str_replace('_', ' ', html_entity_decode($quote["quote_status"]))) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="quote-info">
                    <div class="quote-type__wrapper   quote-info__section">
                        <div class="quote-type__title   quote-section__title">
                            <h1>Service Type</h1>
                        </div>
                        <table class="quote-type">
                            <tr>
                                <td class="td--top"><?= ucwords(str_replace('_', ' ', htmlspecialchars($quote["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="quote-price__wrapper   quote-info__section">
                        <div class="quote-price__title   quote-section__title">
                            <h1>Total Price</h1>
                        </div>
                        <table class="quote-price">
                            <tr>
                                <td class="td--top">₱ <?= number_format($quote["total_price"] ?? 0, 2, '.', ',') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="quote-actions__wrapper   quote-info__section">
                        <div class="quote-actions__title   quote-section__title">
                            <h1>Quote Actions</h1>
                        </div>
                        <table class="quote-actions">
                            <?php if($quote['quote_status'] == "approved"): ?>
                                <tr>
                                    <td class="td--top   td--actions">
                                        <button class="quote-actions__accept   quote-actions__btn" onclick="openModal('accept')">Accept Order</button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($quote['quote_status'] != "cancelled" && $quote['quote_status'] != "accepted"): ?>
                                <tr>
                                    <td class="td--top   td--actions">
                                        <button class="quote-actions__cancel   quote-actions__btn" onclick="openModal('cancel')">Cancel Order</button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="quotes-bottom   quotes-half">
                <div class="quotes-bottom__intro   quotes-half__intro">
                    <h1 class="quotes-bottom__title   quotes-half__title">Quote Items</h1>
                    <p class="quotes-bottom__desc   quotes-half__desc">This is the list of items you have wished to request a quote on.</p>
                </div>
                <div class="quote-items__wrapper">
                    <?php
                        $query = "SELECT * FROM `items` WHERE `quote_id` = :quote_id";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
                        $stmt->execute();

                        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <table class="quote-items">
                        <thead class="quote-items__thead">
                            <tr class="quote-items__tr quote-items__tr--header">
                                <th class="quote-items__th">#</th>
                                <th class="quote-items__th">Furniture Type</th>
                                <th class="quote-items__th">Description</th>
                                <th class="quote-items__th">Quantity</th>
                                <th class="quote-items__th">Price</th>
                                <th class="quote-items__th">Reference Image</th>
                            </tr>
                        </thead>
                        <tbody class="quote-items__tbody">
                            <?php if ($stmt->rowCount() > 0): ?>
                                <?php foreach ($items as $i => $item): ?>
                                    <tr class="quote-items__tr">
                                        <td class="quote-items__td"> <?= $i + 1 ?></td>
                                        <td class="quote-items__td"> <?= ucwords(htmlspecialchars($item["furniture"] ?? 'N/A')) ?> </td>
                                        <td class="quote-items__td"> <?= ucfirst(htmlspecialchars($item["description"] ?? 'N/A')) ?> </td>
                                        <td class="quote-items__td"> <?= htmlspecialchars($item["quantity"] ?? 'N/A') ?> </td>
                                        <td class="quote-items__td"> ₱ <?= number_format($item["item_price"] ?? 0, 2, '.', ',') ?> </td>
                                        <td class="quote-items__td"> 
                                        <?php if (!empty($item["item_img_path"])): ?>
                                            <img src="<?= htmlspecialchars($item["item_img_path"]) ?>" alt="Item image">
                                        <?php else: ?>
                                            None.
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="quote-items__tr">
                                    <td colspan="5">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Accept Order -->
    <div class="modal   modal--accept" id="acceptModal">
        <div class="modal__content">
            <p class="modal__message">Please read our <a href="/legal-agreements.php#cancellation" target="_blank">terms and conditions</a> before accepting the order. Do you want to proceed?</p>
            <input type="checkbox" name="legallyConsented" id="legallyConsented"> I have read and agree to the terms and conditions.
            <button class="modal__action" id="confirmAcceptAction">Accept Order</button>
            <button class="modal__action" id="cancelAcceptAction">Cancel</button>
        </div>
    </div>

    <!-- Modal for Cancel Order -->
    <div class="modal   modal--cancel" id="cancelModal">
        <div class="modal__content">
            <p class="modal__message">Are you sure you want to cancel the order?</p>
            <button class="modal__action" id="confirmCancelAction">Yes, Cancel Order</button>
            <button class="modal__action" id="cancelCancelAction">No</button>
        </div>
    </div>

    <script>
        const quoteId = <?= $quote_id ?>;
    </script>
    <script src="/js/my/quotes.js">
    </script>
</body>
</html>
