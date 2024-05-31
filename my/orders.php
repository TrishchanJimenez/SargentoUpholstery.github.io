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
            `customs` c ON i.custom_id = c.custom_id
                LEFT JOIN
            `delivery` d ON o.order_id = d.order_id
                LEFT JOIN
            `downpayment` dp ON o.order_id = dp.order_id
                LEFT JOIN
            `fullpayment` fp ON o.order_id = fp.order_id
                LEFT JOIN
            `pickup` p ON o.order_id = p.order_id
                LEFT JOIN
            `reviews` r ON o.order_id = r.order_id
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
        <div class="main-wrapper">
            <div class="sidebar__wrapper">
                <div class="sidebar__container">
                    <a href="orders_and_quotes.php" class="quotes__back-button">< Back to Orders and Quotes</a>
                    <div class="sidebar">
                        <ul class="sidebar__list">
                            <li class="sidebar__item">
                                <a href="#overview" class="sidebar__link">Overview</a>
                            </li>
                            <li class="sidebar__item">
                                <a href="#actions" class="sidebar__link">Actions</a>
                            </li>
                            <li class="sidebar__item">
                                <a href="#downpayment" class="sidebar__link">Downpayment</a>
                            </li>
                            <?php if($order['order_phase'] == "pending_fullpayment" || $order['order_phase'] == "out_for_delivery" || $order['order_phase'] == "received"): ?>
                                <li class="sidebar__item">
                                    <a href="#fullpayment" class="sidebar__link">Fullpayment</a>
                                </li>
                            <?php endif ?>
                            <li class="sidebar__item">
                                <a href="#items" class="sidebar__link">Items</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="orders__wrapper">
                <div class="orders"> 
                    <div class="orders-top   orders-segment" id="overview">
                        <div class="orders-top__intro   orders-segment__intro">
                            <h1 class="orders-top__title   orders-segment__title">Order Overview</h1>
                            <p class="orders-top__desc   orders-segment__desc">
                                This is the detailed information about the order you placed. 
                                It includes all the necessary details, actions, and a comprehensive 
                                list of items included in the order.
                            </p>
                        </div>
                        <div class="order-identif">
                            <div class="order-number__wrapper   order-section">
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
                                $phase_class = '';
                                switch ($order["order_phase"]) {
                                    case 'pending_down':
                                        $phase_class = 'status-pending-down';
                                        break;
                                    case 'pickup':
                                        $phase_class = 'status-pickup';
                                        break;
                                    case 'production':
                                        $phase_class = 'status-production';
                                        break;
                                    case 'pending_full':
                                        $phase_class = 'status-pending-full';
                                        break;
                                    case 'delivery':
                                        $phase_class = 'status-delivery';
                                        break;
                                    case 'received':
                                        $phase_class = 'status-received';
                                        break;
                                    default:
                                        $phase_class = 'status-default';
                                        break;
                                }
                            ?>
                            <div class="order-status__wrapper   order-section   <?= $phase_class ?>">
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
                            <div class="order-type__wrapper   order-section">
                                <div class="order-type__title   order-section__title">
                                    <h1>Service Type</h1>
                                </div>
                                <table class="order-type">
                                    <tr>
                                        <td class="td--top"><?= ucwords(str_replace('_', ' ', htmlspecialchars($order["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="order-price__wrapper   order-section">
                                <div class="order-price__title   order-section__title">
                                    <h1>Total Due</h1>
                                </div>
                                <table class="order-price">
                                    <tr>
                                        <td class="td--top">₱ <?= number_format($order["total_price"] ?? 0, 2, '.', ',') ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="order-requisite">
                            <div class="order-payment__wrapper   order-section">
                                <div class="order-payment__title   order-section__title">
                                    <h1>Payment Status</h1>
                                </div>
                                <table class="order-payment">
                                    <tr>
                                        <td class="td--top"><?= ucwords(str_replace('_', ' ', htmlspecialchars($order["payment_phase"] ?? 'N/A'))) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="order-middle   orders-segment" id="actions">
                        <div class="orders-middle__intro   orders-segment__intro">
                            <h1 class="orders-middle__title   orders-segment__title">Quote Actions</h1>
                            <p class="orders-middle__desc   orders-segment__desc">
                                These are the actions that you can perform to your quote.
                            </p>
                        </div>
                        <table class="order-actions">
                            <?php if ($order['order_phase'] == "pending_downpayment"): ?>
                                <?php
                                $_SESSION['enablePickup'] = ($order['service_type'] == "repair") ? true : false;
                                if(!isset($order['delivery_method']) && !isset($order['delivery_address'])): ?>
                                    <tr>
                                        <td>
                                            <button class="order-actions__soa   order-actions__btn" onclick="openModal('soa')">Set Order Address</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if(!isset($order['downpay_method']) && !isset($order['downpay_img_path'])): ?>
                                    <tr>
                                        <td>
                                            <button class="order-actions__upod   order-actions__btn" onclick="openModal('upod')">Upload Proof Of Downpayment</button>
                                        </td>
                                    </tr>
                                <?php elseif(!isset($order['downpay_method']) || !isset($order['downpay_img_path'])): ?>
                                    <tr>
                                        <td>
                                            <button class="order-actions__rupod   order-actions__btn" onclick="openModal('rupod')">Reupload Proof Of Downpayment</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php elseif ($order['order_phase'] == "pending_fullpayment"): ?>
                                <?php if(!isset($order['fullpay_method']) && !isset($order['fullpay_img_path'])): ?>
                                    <tr>
                                        <td>
                                            <button class="order-actions__upof   order-actions__btn" onclick="openModal('upof')">Upload Proof Of Fullpayment</button>
                                        </td>
                                    </tr>
                                <?php elseif(!isset($order['fullpay_method']) || !isset($order['fullpay_img_path'])): ?>
                                    <tr>
                                        <td>
                                            <button class="order-actions__rupof   order-actions__btn" onclick="openModal('rupof')">Reupload Proof Of Fullpayment</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php elseif ($order['order_phase'] == "out_for_delivery"): ?>
                                <tr>
                                    <td>
                                        <button class="order-actions__confirmDelivery   order-actions__btn" onclick="openModal('confirmDelivery')">Confirm Arrival of Delivery</button>
                                    </td>
                                </tr>
                            <?php elseif ($order['order_phase'] == "received"): ?>
                                <?php if(!isset($order['review_id'])): ?>
                                    <tr>
                                        <td>
                                            <button class="order-actions__review   order-actions__btn" onclick="openModal('review')">Review Order</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="td--no-action">
                                        No actions currently available.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="orders-middle   orders-segment" id="downpayment">
                        <div class="orders-middle__intro   orders-segment__intro">
                            <h1 class="orders-middle__title   orders-segment__title">Order Downpayment</h1>
                            <p class="orders-middle__desc   orders-segment__desc">
                                These are the details about your downpayment.
                            </p>
                        </div>
                        <div class="order-downpayment-standing">
                            <div class="payment-status__wrapper   order-section">
                                <div class="payment-status__title   order-section__title">
                                    <h1>Downpayment Status</h1>
                                </div>
                                <table class="payment-status">
                                    <tr>
                                        <td class="td--top">
                                            <?= ucwords(str_replace('_', ' ', htmlspecialchars($order["downpay_verification_status"] ?? 'N/A'))) ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="payment-due__wrapper   order-section">
                                <div class="payment-due__title   order-section__title">
                                    <h1>Downpayment Due</h1>
                                </div>
                                <table class="payment-due">
                                    <tr>
                                        <td class="td--top">₱ <?= number_format(($order["total_price"]/2) ?? 0, 2, '.', ',') ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="order-downpayment-submission">
                            <div class="payment-proof__wrapper   order-section">
                                <div class="payment-proof__title   order-section__title">
                                    <h1>Proof of Downpayment</h1>
                                </div>
                                <table class="payment-proof">
                                    <tr>
                                        <td class="td--top">
                                            <?php if (!empty($order["downpay_img_path"])): ?>
                                                <img src="/<?= htmlspecialchars($order["downpay_img_path"]) ?>" alt="Proof of downpayment" width="200px">
                                            <?php else: ?>
                                                No image uploaded.
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="payment-actions__wrapper   order-section">
                                <div class="payment-actions__title   order-section__title">
                                    <h1>Downpayment Actions</h1>
                                </div>
                                <table class="payment-actions">
                                    <tr>
                                        <?php if(!isset($order['downpay_method']) && !isset($order['downpay_img_path'])): ?>
                                            <tr>
                                                <td>
                                                    <button class="order-actions__upod   order-actions__btn" onclick="openModal('upod')">Upload Proof Of Downpayment</button>
                                                </td>
                                            </tr>
                                        <?php elseif(!isset($order['downpay_method']) || !isset($order['downpay_img_path'])): ?>
                                            <tr>
                                                <td>
                                                    <button class="order-actions__rupod   order-actions__btn" onclick="openModal('rupod')">Reupload Proof Of Downpayment</button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if($order['order_phase'] == "pending_fullpayment" || $order['order_phase'] == "out_for_delivery" || $order['order_phase'] == "received"): ?>
                        <div class="orders-middle   orders-segment" id="fullpayment">
                            <div class="orders-middle__intro   orders-segment__intro">
                                <h1 class="orders-middle__title   orders-segment__title">Order Fullpayment</h1>
                                <p class="orders-middle__desc   orders-segment__desc">
                                    These are the details about your fullpayment
                                </p>
                            </div>
                            <div class="order-fullpayment-standing">
                                <div class="payment-status__wrapper   order-section">
                                    <div class="payment-status__title   order-section__title">
                                        <h1>Fullpayment Status</h1>
                                    </div>
                                    <table class="payment-status">
                                        <tr>
                                            <td class="td--top">
                                                <?= ucwords(str_replace('_', ' ', htmlspecialchars($order["fullpay_verification_status"] ?? 'N/A'))) ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="payment-due__wrapper   order-section">
                                    <div class="payment-due__title   order-section__title">
                                        <h1>Fullpayment Due</h1>
                                    </div>
                                    <table class="payment-due">
                                        <tr>
                                            <td class="td--top">₱ <?= number_format(($order["total_price"]) ?? 0, 2, '.', ',') ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="order-fullpayment-submission">
                                <div class="payment-proof__wrapper   order-section">
                                    <div class="payment-proof__title   order-section__title">
                                        <h1>Proof of Fullpayment</h1>
                                    </div>
                                    <table class="payment-proof">
                                        <tr>
                                            <td class="td--top">
                                                <?php if (!empty($order["fullpay_img_path"])): ?>
                                                    <img src="/<?= htmlspecialchars($order["fullpay_img_path"]) ?>" alt="Proof of fullpayment" width="200px">
                                                <?php else: ?>
                                                    No image uploaded.
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="payment-actions__wrapper   order-section">
                                    <div class="payment-actions__title   order-section__title">
                                        <h1>Fullpayment Actions</h1>
                                    </div>
                                    <table class="payment-actions">
                                        <tr>
                                            <?php if(!isset($order['fullpay_method']) && !isset($order['fullpay_img_path'])): ?>
                                                <tr>
                                                    <td>
                                                        <button class="order-actions__upof   order-actions__btn" onclick="openModal('upof')">Upload Proof Of Fullpayment</button>
                                                    </td>
                                                </tr>
                                            <?php elseif(!isset($order['fullpay_method']) || !isset($order['fullpay_img_path'])): ?>
                                                <tr>
                                                    <td>
                                                        <button class="order-actions__rupof   order-actions__btn" onclick="openModal('rupof')">Reupload Proof Of Fullpayment</button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="orders-bottom   orders-segment" id="items">
                        <div class="orders-bottom__intro   orders-segment__intro">
                            <h1 class="orders-bottom__title   orders-segment__title">Order Items</h1>
                            <p class="orders-bottom__desc   orders-segment__desc">
                                This is the list of items you have placed an order on.
                            </p>
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
                                            <th class="order-items__th" hidden>Description</th>
                                            <th class="order-items__th">Quantity</th>
                                            <th class="order-items__th">Price</th>
                                            <th class="order-items__th" hidden>Reference Image</th>
                                        </tr>
                                    </thead>
                                    <tbody class="order-items__tbody">
                                        <?php if ($stmt->rowCount() > 0): ?>
                                            <?php foreach ($items as $i => $item): ?>
                                                <tr class="order-items__tr   order-items__tr--td   items__tr">
                                                    <td class="order-items__td"> <?= $i + 1 ?></td>
                                                    <td class="order-items__td"> <?= ucwords(htmlspecialchars($item["furniture"] ?? 'N/A')) ?> </td>
                                                    <td class="order-items__td" hidden> <?= ucfirst(htmlspecialchars($item["description"] ?? 'N/A')) ?> </td>
                                                    <td class="order-items__td"> <?= htmlspecialchars($item["quantity"] ?? 'N/A') ?> </td>
                                                    <td class="order-items__td"> ₱ <?= number_format($item["item_price"] ?? 0, 2, '.', ',') ?> </td>
                                                    <td class="order-items__td" hidden> 
                                                    <?php if (!empty($item["item_ref_img"])): ?>
                                                        <img src="/<?= htmlspecialchars($item["item_ref_img"]) ?>" alt="Item image" width="200px">
                                                    <?php else: ?>
                                                        No image uploaded.
                                                    <?php endif; ?>
                                                    </td>

                                                    <td class="order-items__td" hidden><?= htmlspecialchars($item["custom_id"] ?? '') ?></td>
                                                    <td class="order-items__td" hidden><?= htmlspecialchars($item["dimensions"] ?? 'None.') ?></td>
                                                    <td class="order-items__td" hidden><?= htmlspecialchars($item["materials"] ?? 'None.') ?></td>
                                                    <td class="order-items__td" hidden><?= htmlspecialchars($item["fabric"] ?? 'None.') ?></td>
                                                    <td class="order-items__td" hidden><?= htmlspecialchars($item["color"] ?? 'None.') ?></td>
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
        </div>

        <!-- All Modals -->
        <?php include_once('item_details.php') ?>
        <?php include_once('set_order_address.php'); ?>
        <?php include_once('upload_proof_of_downpayment.php'); ?>
        <?php include_once('reupload_proof_of_downpayment.php'); ?>
        <?php include_once('upload_proof_of_fullpayment.php'); ?>
        <?php include_once('reupload_proof_of_fullpayment.php'); ?>
        <?php include_once('confirm_arrival.php'); ?>
        <?php include_once('submit_review.php'); ?>

        <script src="/js/my/orders.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const quoteId = <?= $order['quote_id'] ?>;
                const itemsPerPage = 10;
                let currentPage = 1;

                function fetchItems(page) {
                    fetch(`/api/orders_pagination.php?order_id=${quoteId}&page=${page}`)
                        .then(response => {
                            // console.log(response.text());
                            return response.json();
                        })
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
        <script src="/js/globals.js"></script>
    </body>
</html>
