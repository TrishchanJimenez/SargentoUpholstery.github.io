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
    <div class="main-wrapper">
        <div class="sidebar__wrapper">
            <div class="sidebar__container">
                <div class="sidebar__back">
                    <a href="orders_and_quotes.php" class="quotes__back-button">< Back to Orders and Quotes</a>
                </div>
                <div class="sidebar">
                    <ul class="sidebar__list">
                        <li class="sidebar__item">
                            <a href="#overview" class="sidebar__link">Overview</a>
                        </li>
                        <li class="sidebar__item">
                            <a href="#actions" class="sidebar__link">Actions</a>
                        </li>
                        <li class="sidebar__item">
                            <a href="#items" class="sidebar__link">Items</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="quotes__wrapper">
            <div class="quotes"> 
                <div class="quotes-top   quotes-segment" id="overview">
                    <div class="quotes-top__intro   quotes-segment__intro">
                        <h1 class="quotes-top__title   quotes-segment__title">Quote Information</h1>
                        <p class="quotes-top__desc   quotes-segment__desc">
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
                    </div>
                </div>
                <div class="quote-middle   quotes-segment" id="actions">
                    <div class="quotes-middle__intro   quotes-segment__intro">
                        <h1 class="quotes-middle__title   quotes-segment__title">Quote Actions</h1>
                        <p class="quotes-middle__desc   quotes-segment__desc">
                            These are the actions that you can perform to your quote.
                        </p>
                    </div>
                    <table class="quote-actions">
                        <?php if($quote['quote_status'] == "approved"): ?>
                            <tr>
                                <td class="td--top   td--actions">
                                    <button class="quote-actions__accept   quote-actions__btn" onclick="openModal('accept')">Accept Order</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if($quote['quote_status'] != "cancelled" && $quote['quote_status'] != "rejected" && $quote['quote_status'] != "accepted"): ?>
                            <tr>
                                <td class="td--top   td--actions">
                                    <button class="quote-actions__cancel   quote-actions__btn" onclick="openModal('cancel')">Cancel Order</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="quotes-bottom   quotes-segment" id="items">
                    <div class="quotes-bottom__intro   quotes-segment__intro">
                        <h1 class="quotes-bottom__title   quotes-segment__title">Quote Items</h1>
                        <p class="quotes-bottom__desc   quotes-segment__desc">This is the list of items you have wished to request a quote on.</p>
                    </div>
                    <div class="quote-items__wrapper">
                        <?php
                            $items_per_page = 5;
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $items_per_page;
                        
                            $query = "SELECT * FROM `items` WHERE `quote_id` = :quote_id LIMIT :limit OFFSET :offset";
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
                            $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
                            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                            $stmt->execute();
                            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $query_total = "SELECT COUNT(*) as total FROM `items` WHERE `quote_id` = :quote_id";
                            $stmt_total = $conn->prepare($query_total);
                            $stmt_total->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
                            $stmt_total->execute();
                            $total_items = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
                            $total_pages = ceil($total_items / $items_per_page);
                        ?>
                        <div id="quote-items-container">
                            <table class="quote-items">
                                <thead class="quote-items__thead">
                                    <tr class="quote-items__tr quote-items__tr--header">
                                        <th class="quote-items__th">#</th>
                                        <th class="quote-items__th">Furniture Type</th>
                                        <th class="quote-items__th" hidden>Description</th>
                                        <th class="quote-items__th">Quantity</th>
                                        <th class="quote-items__th">Price</th>
                                        <th class="quote-items__th" hidden>Reference Image</th>
                                    </tr>
                                </thead>
                                <tbody class="quote-items__tbody">
                                    <?php if ($stmt->rowCount() > 0): ?>
                                        <?php foreach ($items as $i => $item): ?>
                                            <tr class="quote-items__tr">
                                                <td class="quote-items__td"> <?= $i + 1 ?></td>
                                                <td class="quote-items__td"> <?= ucwords(htmlspecialchars($item["furniture"] ?? 'N/A')) ?> </td>
                                                <td class="quote-items__td" hidden> <?= ucfirst(htmlspecialchars($item["description"] ?? 'N/A')) ?> </td>
                                                <td class="quote-items__td"> <?= htmlspecialchars($item["quantity"] ?? 'N/A') ?> </td>
                                                <td class="quote-items__td"> ₱ <?= number_format($item["item_price"] ?? 0, 2, '.', ',') ?> </td>
                                                <td class="quote-items__td" hidden> 
                                                <?php if (!empty($item["item_ref_img"])): ?>
                                                    <img src="/<?= htmlspecialchars($item["item_ref_img"]) ?>" alt="Item image" width="200px">
                                                <?php else: ?>
                                                    No image uploaded.
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
                        <div class="pagination" id="pagination-controls">
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?quote_id=<?= $quote_id ?>&page=<?= $page - 1 ?>">&laquo; Previous</a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?quote_id=<?= $quote_id ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <a href="?quote_id=<?= $quote_id ?>&page=<?= $page + 1 ?>">Next &raquo;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Item Details -->
    <div class="modal   modal--item-details" id="itemDetailsModal">
        <div class="modal__content   modal__content--item-details">
            <span class="modal__close" id="closeItemDetails">&times;</span>
            <h2 class="modal__title">Item Details</h2>
            <table class="modal__table">
                <tr>
                    <th>Furniture:</th>
                    <td id="modalFurniture"></td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td id="modalDescription"></td>
                </tr>
                <tr>
                    <th>Quantity:</th>
                    <td id="modalQuantity"></td>
                </tr>
                <tr>
                    <th>Price:</th>
                    <td id="modalPrice"></td>
                </tr>
                <tr>
                    <th>Reference Image:</th>
                    <td id="modalRefImage"></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Modal for Accept Order -->
    <div class="modal   modal--accept" id="acceptModal">
        <div class="modal__content   modal__content--accept">
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
    <script src="/js/my/quotes.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quoteId = <?= $quote_id ?>;
            const itemsPerPage = 10;
            let currentPage = 1;

            function fetchItems(page) {
                fetch(`../api/quotes_pagination.php?quote_id=${quoteId}&page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data.items);
                        updatePagination(data.totalPages, page);
                    })
                    .catch(error => console.error('Error fetching items:', error));
            }

            function updateTable(items) {
                const tableBody = document.querySelector('.quote-items__tbody');
                tableBody.innerHTML = ''; // Clear existing rows
                items.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'quote-items__tr';
                    row.innerHTML = `
                        <td class="quote-items__td">${index + 1}</td>
                        <td class="quote-items__td">${item.furniture}</td>
                        <td class="quote-items__td">${item.description}</td>
                        <td class="quote-items__td">${item.quantity}</td>
                        <td class="quote-items__td">₱ ${item.item_price}</td>
                        <td class="quote-items__td"><img src="/${item.item_ref_img}" alt="Item image" width="200px"></td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            function updatePagination(totalPages, currentPage) {
                const paginationControls = document.getElementById('pagination-controls');
                paginationControls.innerHTML = ''; // Clear existing controls

                if (currentPage > 1) {
                    const prevLink = document.createElement('a');
                    prevLink.href = `#`;
                    prevLink.innerHTML = '&laquo; Previous';
                    prevLink.addEventListener('click', () => fetchItems(currentPage - 1));
                    paginationControls.appendChild(prevLink);
                }

                for (let i = 1; i <= totalPages; i++) {
                    const pageLink = document.createElement('a');
                    pageLink.href = `#`;
                    pageLink.innerText = i;
                    pageLink.className = i === currentPage ? 'active' : '';
                    pageLink.addEventListener('click', () => fetchItems(i));
                    paginationControls.appendChild(pageLink);
                }

                if (currentPage < totalPages) {
                    const nextLink = document.createElement('a');
                    nextLink.href = `#`;
                    nextLink.innerHTML = 'Next &raquo;';
                    nextLink.addEventListener('click', () => fetchItems(currentPage + 1));
                    paginationControls.appendChild(nextLink);
                }
            }

            fetchItems(currentPage);
        });
    </script>
</body>
</html>
