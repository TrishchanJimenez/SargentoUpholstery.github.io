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
            * 
        FROM
            `orders` o
                INNER JOIN
            `quotes` q ON o.quote_id = q.quote_id
                INNER JOIN
            `items` i ON q.quote_id = i.quote_id
                LEFT JOIN
            `customs` c ON i.item_id = c.item_id    
        WHERE
            o.order_id = :order_id
                AND
            q.customer_id = :customer_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Order not found or you do not have permission to view this order.";
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
    <title>Order Details - Sargento Upholstery</title>
</head>

<body>
    <?php require_once('../header.php'); ?>
    <div class="orders__wrapper">
        <a href="orders_and_quotes.php" class="orders__back-button">< Back to Orders and Quotes</a>
        <div class="orders"> 
            <div class="orders-top   orders-half">
                <div class="orders-top__intro   orders-half__intro">
                    <h1 class="orders-top__title   orders-half__title">Order Information</h1>
                    <p class="orders-top__desc   orders-half__desc">
                        This is the detailed information about the order you placed. 
                        It includes all the necessary details, actions, and a comprehensive 
                        list of items included in the order.
                    </p>
                </div>
                <div class="order-identif">
                    <div class="order-number__wrapper   order-identif__section">
                        <div class="order-number__title   order-section__title">
                            <h1>Order Number: </h1>
                        </div>
                        <table class="order-number">
                            <tr>
                                <td class="td--top"><?= ($order["order_id"]) ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php
                        $status_class = '';
                        switch ($order["order_phase"]) {
                            default:
                                $status_class = 'status-default';
                                break;
                        }
                    ?>
                    <div class="order-status__wrapper   order-identif__section   <?= $status_class ?>">
                        <div class="order-status__title order-section__title">
                            <h1>Current Phase: </h1>
                        </div>
                        <table class="order-status">
                            <tr>
                                <td class="td--top"><?= ucwords(str_replace('_', ' ', html_entity_decode($order["order_phase"]))) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="order-info">
                    <div class="order-type__wrapper   order-info__section">
                        <div class="order-type__title   order-section__title">
                            <h1>Service Type</h1>
                        </div>
                        <table class="order-type">
                            <tr>
                                <td class="td--top"><?= ucwords(str_replace('_', ' ', htmlspecialchars($order["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="order-price__wrapper   order-info__section">
                        <div class="order-price__title   order-section__title">
                            <h1>Total Price</h1>
                        </div>
                        <table class="order-price">
                            <tr>
                                <td class="td--top">₱ <?= number_format($order["total_price"] ?? 0, 2, '.', ',') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="order-actions__wrapper   order-info__section">
                        <div class="order-actions__title   order-section__title">
                            <h1>Order Actions</h1>
                        </div>
                        <table class="order-actions">
                            <?php if ($order['order_phase'] == "pending_downpayment"): ?>
                                <?php
                                $_SESSION['enablePickup'] = ($order['service_type'] == "repair") ? true : false;
                                if(!isset($order['del_method']) && !isset($order['del_address_id'])): ?>
                                    <tr>
                                        <td>
                                            <?php include_once('set_order_address.php'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if(!isset($order['downpayment_method']) && !isset($order['downpayment_img'])): ?>
                                    <tr>
                                        <td>
                                            <?php include_once('upload_proof_of_downpayment.php'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php elseif ($order['order_phase'] == "pending_fullpayment"): ?>
                                <?php if(!isset($order['fullpayment_method']) && !isset($order['fullpayment_img'])): ?>
                                    <tr>
                                        <td>
                                            <?php include_once('upload_proof_of_fullpayment.php'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php elseif ($order['order_phase'] == "out_for_delivery"): ?>
                                <tr>
                                    <td>
                                        <?php include_once('confirm_arrival.php'); ?>
                                    </td>
                                </tr>
                            <?php elseif ($order['order_phase'] == "received"): ?>
                                <?php if(!isset($order['review_id'])): ?>
                                    <tr>
                                        <td>
                                            <?php include_once('submit_review.php'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                No actions currently available. <br>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="orders-bottom   orders-half">
                <div class="orders-bottom__intro   orders-half__intro">
                    <h1 class="orders-bottom__title   orders-half__title">Order Items</h1>
                    <p class="orders-bottom__desc   orders-half__desc">This is the list of items you have placed an order on.</p>
                </div>
                <div class="order-items__wrapper">
                    <?php
                        $items_per_page = 5;
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $items_per_page;
                    
                        $query = "
                            SELECT 
                                * 
                            FROM 
                                `items` i
                            WHERE 
                                i.quote_id = :quote_id
                            LIMIT :limit 
                            OFFSET :offset
                        ";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':quote_id', $order['quote_id'], PDO::PARAM_INT);
                        $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $stmt->execute();
                        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $query_total = "
                            SELECT 
                                COUNT(*) as total 
                            FROM 
                                `items`
                            WHERE 
                                `quote_id` = :quote_id
                        ";
                        $stmt_total = $conn->prepare($query_total);
                        $stmt_total->bindParam(':quote_id', $order['quote_id'], PDO::PARAM_INT);
                        $stmt_total->execute();
                        $total_items = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
                        $total_pages = ceil($total_items / $items_per_page);
                    ?>
                    <div id="order-items-container">
                        <table class="order-items">
                            <thead class="order-items__thead">
                                <tr class="order-items__tr order-items__tr--header">
                                    <th class="order-items__th">#</th>
                                    <th class="order-items__th">Furniture Type</th>
                                    <th class="order-items__th">Description</th>
                                    <th class="order-items__th">Quantity</th>
                                    <th class="order-items__th">Price</th>
                                    <th class="order-items__th">Reference Image</th>
                                </tr>
                            </thead>
                            <tbody class="order-items__tbody">
                                <?php if ($stmt->rowCount() > 0): ?>
                                    <?php foreach ($items as $i => $item): ?>
                                        <tr class="order-items__tr">
                                            <td class="order-items__td"> <?= $i + 1 ?></td>
                                            <td class="order-items__td"> <?= ucwords(htmlspecialchars($item["furniture"] ?? 'N/A')) ?> </td>
                                            <td class="order-items__td"> <?= ucfirst(htmlspecialchars($item["description"] ?? 'N/A')) ?> </td>
                                            <td class="order-items__td"> <?= htmlspecialchars($item["quantity"] ?? 'N/A') ?> </td>
                                            <td class="order-items__td"> ₱ <?= number_format($item["item_price"] ?? 0, 2, '.', ',') ?> </td>
                                            <td class="order-items__td"> 
                                            <?php if (!empty($item["item_ref_img"])): ?>
                                                <img src="/<?= htmlspecialchars($item["item_ref_img"]) ?>" alt="Item image" width="200px">
                                            <?php else: ?>
                                                No image uploaded.
                                            <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr class="order-items__tr">
                                        <td colspan="5">No records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination" id="pagination-controls">
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?order_id=<?= $order_id ?>&page=<?= $page - 1 ?>">&laquo; Previous</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?order_id=<?= $order_id ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?order_id=<?= $order_id ?>&page=<?= $page + 1 ?>">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Item Details -->
    <div class="modal   modal--item-details" id="itemDetailsModal">
        <div class="modal__content">
            <span class="modal__close" id="closeItemDetails">&times;</span>
            <h2 class="modal__title">Item Details</h2>
            <table class="modal__table">
                <tr>
                    <th>Furniture Type:</th>
                    <td id="modalFurnitureType"></td>
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

    <script>
        const orderId = <?= $order_id ?>;
    </script>
    <script src="/js/my/orders.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderId = <?= $order_id ?>;
            const itemsPerPage = 10;
            let currentPage = 1;

            function fetchItems(page) {
                fetch(`../api/orders_pagination.php?order_id=${orderId}&page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data.items);
                        updatePagination(data.totalPages, page);
                    })
                    .catch(error => console.error('Error fetching items:', error));
            }

            function updateTable(items) {
                const tableBody = document.querySelector('.order-items__tbody');
                tableBody.innerHTML = ''; // Clear existing rows
                items.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'order-items__tr';
                    row.innerHTML = `
                        <td class="order-items__td">${index + 1}</td>
                        <td class="order-items__td">${item.furniture}</td>
                        <td class="order-items__td">${item.description}</td>
                        <td class="order-items__td">${item.quantity}</td>
                        <td class="order-items__td">₱ ${item.item_price}</td>
                        <td class="order-items__td"><img src="/${item.item_ref_img}" alt="Item image" width="200px"></td>
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
