<?php
require_once '../database_connection.php'; // Include database connection script

$sqlW = "SELECT furniture_type, COUNT(*) AS order_count
        FROM orders O
        JOIN order_date USING(order_id)
        WHERE WEEK(placement_date) = WEEK(CURDATE()) AND YEAR(placement_date) = YEAR(CURDATE()) AND order_status = 'received'
        GROUP BY furniture_type
        ORDER BY furniture_type";

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
