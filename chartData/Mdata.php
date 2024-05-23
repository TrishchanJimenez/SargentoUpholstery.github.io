<?php
require_once '../database_connection.php'; // Include database connection script

$sqlM = "SELECT o.furniture_type, DATE_FORMAT(od.placement_date, '%Y-%m-01') AS month_start, COUNT(*) AS order_count 
        FROM orders o 
        JOIN order_date od ON o.order_id = od.order_id 
        WHERE od.placement_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
        GROUP BY o.furniture_type, DATE_FORMAT(od.placement_date, '%Y-%m-01')
        ORDER BY o.furniture_type, od.placement_date";

$stmtM = $conn->prepare($sqlM);
$stmtM->execute();
$rowsM = $stmtM->fetchAll(PDO::FETCH_ASSOC);

$dataM = [
    'labels' => [],
    'values' => []
];

foreach ($rowsM as $rowM) {
    $dataM['labels'][] = $rowM['furniture_type']; // Assuming 'furniture_type' is already in correct format
    $dataM['values'][] = (int) $rowM['order_count'];
}

// Check if there are no rows returned
if (empty($dataM['labels'])) {
    $dataM = [
        'labels' => [], // Empty array for labels
        'values' => []  // Empty array for values
    ];
}

header('Content-Type: application/json');
echo json_encode($dataM);
?>
