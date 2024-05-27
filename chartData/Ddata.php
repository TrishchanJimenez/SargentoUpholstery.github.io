<?php
require_once '../database_connection.php'; // Include database connection script

$sqlD = "
    SELECT 
        furniture_type, 
        COUNT(*) AS order_count
    FROM orders O
    JOIN order_date USING(order_id)
    WHERE 
        order_status NOT IN ('received', 'new_order')
        AND is_cancelled = 0
    GROUP BY furniture_type
    ORDER BY furniture_type";

$stmtD = $conn->prepare($sqlD);
$stmtD->execute();
$rowsD = $stmtD->fetchAll(PDO::FETCH_ASSOC);

$dataD = [
    'labels' => [], // Initialize labels array
    'datasets' => [
        [
            'label' => 'Order Count',
            'data' => [], // Initialize data array
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

// Populate labels and data arrays
foreach ($rowsD as $rowD) {
    $dataD['labels'][] = $rowD['furniture_type']; // Add furniture_type to labels array
    $dataD['datasets'][0]['data'][] = (int) $rowD['order_count']; // Add order_count to data array
}

// If no data fetched, ensure empty arrays
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
