<?php
// Include database connection here
require_once '../database_connection.php';

// Check if interval parameter is set in GET request
$sql = "
    SELECT
        service_type AS order_type,
        COUNT(*) AS count
    FROM
        orders O
    JOIN
        quotes Q USING(quote_id)
    WHERE
        WEEK(O.created_at) = WEEK(CURDATE())
        AND YEAR(O.created_at) = YEAR(CURDATE())
        AND order_phase NOT IN ('received', 'cancelled')
    GROUP BY order_type
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data structure for JSON response
$data = [
    'labels' => [], // Initialize labels array
    'datasets' => [
        [
            'label' => ' Status',
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

foreach ($rows as $row) {
    $type = $row['order_type'] === 'mto' ? 'Made to Order' : 'Repair';
    $data['labels'][] = $type;
    $data['datasets'][0]['data'][] = (int) $row['count'];
}
    
// Return JSON response
echo json_encode($data);
?>

