<?php
    require '../database_connection.php';
    header('Content-Type: application/json');

    $items_per_page = 10;
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

    echo json_encode([
        'items' => $items,
        'totalPages' => $total_pages
    ]);
    exit;
?>