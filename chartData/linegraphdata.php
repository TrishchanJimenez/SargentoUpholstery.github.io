<?php
require_once '../database_connection.php'; // Adjust path as needed

try {
    // Daily data
    $sqlDaily = "SELECT DATE(od.placement_date) AS placement_day, AVG(o.quoted_price) AS avg_quoted_price FROM orders o JOIN order_date od ON o.order_id = od.order_id WHERE od.placement_date <= CURDATE() - INTERVAL 1 DAY AND o.order_status = 'received' GROUP BY DATE(od.placement_date) ORDER BY placement_day;";

    $stmtDaily = $conn->prepare($sqlDaily);
    $stmtDaily->execute();
    $rowsDaily = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    $dataDaily = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Daily Average Quoted Price',
                'data' => [],
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1
            ]
        ]
    ];

    if ($stmtDaily->rowCount() > 0) {
        foreach ($rowsDaily as $row) {
            $dataDaily['labels'][] = $row['placement_day'];
            $dataDaily['datasets'][0]['data'][] = (float) $row['avg_quoted_price'];
        }
    }

    // Weekly data
    $sqlWeekly = "SELECT CONCAT(YEAR(od.placement_date), '-', WEEK(od.placement_date)) AS placement_week,
                AVG(o.quoted_price) AS avg_quoted_price
                FROM orders o
                JOIN order_date od ON o.order_id = od.order_id
                WHERE od.placement_date <= CURDATE() - INTERVAL 1 WEEK AND
                o.order_status = 'received'
                GROUP BY placement_week
                ORDER BY placement_week";

    $stmtWeekly = $conn->prepare($sqlWeekly);
    $stmtWeekly->execute();
    $rowsWeekly = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

    $dataWeekly = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Weekly Average Quoted Price',
                'data' => [],
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            ]
        ]
    ];

    if ($stmtWeekly->rowCount() > 0) {
        foreach ($rowsWeekly as $row) {
            $dataWeekly['labels'][] = $row['placement_week'];
            $dataWeekly['datasets'][0]['data'][] = (float) $row['avg_quoted_price'];
        }
    }

    // Monthly data
    $sqlMonthly = "SELECT DATE_FORMAT(od.placement_date, '%Y-%m') AS placement_month,
                AVG(o.quoted_price) AS avg_quoted_price
                FROM orders o
                JOIN order_date od ON o.order_id = od.order_id
                WHERE od.placement_date <= CURDATE() - INTERVAL 1 MONTH AND
                o.order_status = 'received'
                GROUP BY placement_month
                ORDER BY placement_month";

    $stmtMonthly = $conn->prepare($sqlMonthly);
    $stmtMonthly->execute();
    $rowsMonthly = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

    $dataMonthly = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Monthly Average Quoted Price',
                'data' => [],
                'backgroundColor' => 'rgba(255, 205, 86, 0.2)',
                'borderColor' => 'rgba(255, 205, 86, 1)',
                'borderWidth' => 1
            ]
        ]
    ];

    if ($stmtMonthly->rowCount() > 0) {
        foreach ($rowsMonthly as $row) {
            $dataMonthly['labels'][] = $row['placement_month'];
            $dataMonthly['datasets'][0]['data'][] = (float) $row['avg_quoted_price'];
        }
    }

    // Combine all data into one array
    $data = [
        'daily' => $dataDaily,
        'weekly' => $dataWeekly,
        'monthly' => $dataMonthly
    ];

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>
