<?php
// Include database connection here
require_once '../database_connection.php';

// Check if interval parameter is set in GET request
if (!isset($_GET['interval'])) {
    echo json_encode(['error' => 'Interval parameter is missing']);
    exit();
}

$interval = $_GET['interval'];
$sql = "";

switch ($interval) {
    case 'Daily':
        $sql = "
            SELECT
                today_pending_orders.today AS today_pending_count,
                orders.order_status,
                COUNT(*) AS status_count
            FROM 
                orders
            JOIN
                order_date USING(order_id)
            JOIN (
                SELECT 
                    COUNT(*) AS today,
                    order_status
                FROM 
                    orders 
                JOIN 
                    order_date USING(order_id) 
                WHERE 
                    DATE(placement_date) = CURDATE() 
                GROUP BY 
                    order_status
            ) AS today_pending_orders ON orders.order_status = today_pending_orders.order_status
            WHERE 
                DATE(placement_date) = CURDATE()  -- Filter for orders placed today
            GROUP BY 
                orders.order_status, today_pending_orders.today
            ORDER BY 
                orders.order_status;
        ";
        break;
    
    case 'Weekly':
        $sql = "
            SELECT
                DATE_FORMAT(placement_date, '%x-%v') AS week,
                orders.order_status,
                COUNT(*) AS status_count
            FROM 
                orders
            JOIN
                order_date USING(order_id)
            WHERE 
                YEARWEEK(placement_date, 1) = YEARWEEK(CURDATE(), 1)  -- Filter for current week
            GROUP BY 
                week, orders.order_status
            ORDER BY 
                week, orders.order_status;
        ";
        break;
    
    case 'Monthly':
        $sql = "
            SELECT
                DATE_FORMAT(placement_date, '%Y-%m') AS month,
                orders.order_status,
                COUNT(*) AS status_count
            FROM 
                orders
            JOIN
                order_date USING(order_id)
            WHERE 
                YEAR(placement_date) = YEAR(CURDATE())  -- Filter for current year
                AND MONTH(placement_date) = MONTH(CURDATE())  -- Filter for current month
            GROUP BY 
                month, orders.order_status
            ORDER BY 
                month, orders.order_status;
        ";
        break;
    
    default:
        echo json_encode(['error' => 'Invalid interval specified']);
        exit();
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data structure for JSON response
$data = [
    'labels' => [], // Initialize labels array
    'datasets' => [
        [
            'label' => ucfirst($interval) . ' Status',
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
switch ($interval) {
    case 'Daily':
        foreach ($rows as $row) {
            $data['labels'][] = $row['order_status'];
            $data['datasets'][0]['data'][] = (int) $row['status_count'];
        }
        break;
    
    case 'Weekly':
        foreach ($rows as $row) {
            $data['labels'][] = $row['order_status'];
            $data['datasets'][0]['data'][] = (int) $row['status_count'];
        }
        break;
    
    case 'Monthly':
        foreach ($rows as $row) {
            $data['labels'][] = $row['order_status'];
            $data['datasets'][0]['data'][] = (int) $row['status_count'];
        }
        break;
    
    default:
        break;
}

// Return JSON response
echo json_encode($data);
?>
