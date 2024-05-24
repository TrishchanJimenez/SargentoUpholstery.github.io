<?php
require_once '../database_connection.php'; // Include database connection script

$sqlM = "SELECT furniture_type, COUNT(*) AS order_count
        FROM orders O
        JOIN order_date USING(order_id)
        WHERE MONTH(placement_date) = MONTH(CURDATE()) AND YEAR(placement_date) = YEAR(CURDATE()) AND order_status = 'received'
        GROUP BY furniture_type
        ORDER BY furniture_type";

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
