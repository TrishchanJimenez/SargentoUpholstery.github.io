<?php
require_once('../database_connection.php'); // Adjust the path as per your file structure

function fetchData($interval) {
    global $conn;

    switch ($interval) {
        case 'Daily':
            $sql = "
            SELECT
                today_pending_orders.today AS today_pending_count,
                orders.order_type,
                COUNT(*) AS status_count
            FROM 
                orders
            JOIN
                order_date USING(order_id)
            JOIN (
                SELECT 
                    COUNT(*) AS today,
                    order_type
                FROM 
                    orders 
                JOIN 
                    order_date USING(order_id) 
                WHERE 
                    DATE(placement_date) = CURDATE() 
                GROUP BY 
                    order_type
            ) AS today_pending_orders ON orders.order_type = today_pending_orders.order_type
            WHERE 
                DATE(placement_date) = CURDATE()  -- Filter for orders placed today
            GROUP BY 
                orders.order_type, today_pending_orders.today
            ORDER BY 
                orders.order_type;
            ";
            break;
        case 'Weekly':
            $sql = "
                SELECT
                    DATE_FORMAT(placement_date, '%x-%v') AS week,
                    orders.order_type,
                    COUNT(*) AS status_count
                FROM 
                    orders
                JOIN
                    order_date USING(order_id)
                WHERE 
                    YEARWEEK(placement_date, 1) = YEARWEEK(CURDATE(), 1)  -- Filter for current week
                GROUP BY 
                    week, orders.order_type
                ORDER BY 
                    week, orders.order_type;
            ";
            break;
        case 'Monthly':
            $sql = "
                SELECT
                    DATE_FORMAT(placement_date, '%Y-%m') AS month,
                    orders.order_type,
                    COUNT(*) AS status_count
                FROM 
                    orders
                JOIN
                    order_date USING(order_id)
                WHERE 
                    YEAR(placement_date) = YEAR(CURDATE())  -- Filter for current year
                    AND MONTH(placement_date) = MONTH(CURDATE())  -- Filter for current month
                GROUP BY 
                    month, orders.order_type
                ORDER BY 
                    month, orders.order_type;
            ";
            break;
        default:
            // Invalid interval
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
    foreach ($rows as $row) {
        switch ($interval) {
            case 'Daily':
                $data['labels'][] = $row['order_type'];
                $data['datasets'][0]['data'][] = (int) $row['status_count'];
                break;
            case 'Weekly':
                $data['labels'][] = $row['order_type'];
                $data['datasets'][0]['data'][] = (int) $row['status_count'];
                break;
            case 'Monthly':
                $data['labels'][] = $row['order_type'];
                $data['datasets'][0]['data'][] = (int) $row['status_count'];
                break;
            default:
                break;
        }
    }

    // Return JSON response
    return json_encode($data);
}

// Check if interval parameter is set in GET request
if (isset($_GET['interval'])) {
    $interval = $_GET['interval'];

    // Fetch data based on the interval and output JSON
    echo fetchData($interval);
} else {
    // Error handling if interval parameter is missing
    echo json_encode(['error' => 'Interval parameter is missing']);
}
?>
