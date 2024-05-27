<?php
// Include database connection here
require_once '../database_connection.php';
$sql = "
    SELECT
        order_status,    
        COUNT(*) AS status_count 
    FROM
        orders
    JOIN
        order_date USING(order_id)
    WHERE
        order_status NOT IN('new_order', 'received')
        AND WEEK(placement_date) = WEEK(CURDATE())
        AND YEAR(placement_date) = YEAR(CURDATE())
    GROUP BY order_status
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data structure for JSON response
$data = [
    'labels' => [], // Initialize labels array
    'datasets' => [
        [
            'label' => 'Status',
            'data' => [], // Initialize data array
            'backgroundColor' => [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            'borderColor' => [
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

// Populate labels and data arrays based on interval
foreach ($rows as $row) {
    $status = ucwords(str_replace('_', ' ', $row['order_status']));
    $data['labels'][] = $status;
    $data['datasets'][0]['data'][] = (int) $row['status_count'];
}

// Return JSON response
echo json_encode($data);
?>