<?php
require_once '../database_connection.php'; // Include database connection script

$sqlW = "SELECT o.furniture_type, CONCAT(YEAR(od.placement_date), '-', WEEK(od.placement_date)) AS week_start, COUNT(*) AS order_count 
        FROM orders o 
        JOIN order_date od ON o.order_id = od.order_id 
        WHERE od.placement_date >= CURDATE() - INTERVAL 1 WEEK
        GROUP BY o.furniture_type, YEAR(od.placement_date), WEEK(od.placement_date)
        ORDER BY o.furniture_type, od.placement_date";

$stmtW = $conn->prepare($sqlW);
$stmtW->execute();
$rowsW = $stmtW->fetchAll(PDO::FETCH_ASSOC);

$dataW = [
    'labels' => [],
    'values' => []
];

foreach ($rowsW as $rowW) {
    $dataW['labels'][] = $rowW['furniture_type']; // Assuming 'furniture_type' is already in correct format
    $dataW['values'][] = (int) $rowW['order_count'];
}

// Check if there are no rows returned
if (empty($dataW['labels'])) {
    $dataW = [
        'labels' => [], // Empty array for labels
        'values' => []  // Empty array for values
    ];
}

header('Content-Type: application/json');
echo json_encode($dataW);
?>
