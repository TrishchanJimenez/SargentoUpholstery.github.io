<?php
require_once '../database_connection.php'; // Include database connection script

$sqlD = "SELECT o.furniture_type, DATE_FORMAT(od.placement_date, '%Y-%m-%d') AS day_start, COUNT(*) AS order_count 
        FROM orders o 
        JOIN order_date od ON o.order_id = od.order_id 
        WHERE od.placement_date >= CURDATE()
        GROUP BY o.furniture_type, DAY(od.placement_date)
        ORDER BY o.furniture_type, od.placement_date";

$stmtD = $conn->prepare($sqlD);
$stmtD->execute();
$rowsD = $stmtD->fetchAll(PDO::FETCH_ASSOC);

$dataD = [
    'labels' => [],
    'datasets' => [
        [
            'label' => 'Order Count', // Dataset label
            'data' => [], // Data array (will be populated below)
            'backgroundColor' => [ // Colors for bars (optional)
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            'borderColor' => [ // Border colors for bars (optional)
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            'borderWidth' => 1
        ]
    ]
];

foreach ($rowsD as $rowD) {
    $dataD['labels'][] = $rowD['furniture_type']; // Assuming 'furniture_type' is already in correct format
    $dataD['datasets'][0]['data'][] = (int) $rowD['order_count'];
}

// Check if there are no rows returned
if (empty($dataD['labels'])) {
    $dataD = [
        'labels' => [], // Empty array for labels
        'datasets' => [
            [
                'label' => 'Order Count',
                'data' => [], // Empty array for data
                'backgroundColor' => [], // Empty array for backgroundColor
                'borderColor' => [], // Empty array for borderColor
                'borderWidth' => 1
            ]
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($dataD);
?>
