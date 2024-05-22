<?php
    // Include database connection
    include_once("../database_connection.php");

    // Fetch customers and their latest messages
    $query = "
        SELECT 
            u.user_id, 
            u.name AS customer_name, 
            MAX(m.message) AS message, 
            MAX(m.timestamp) AS timestamp
        FROM 
            users u
                LEFT JOIN 
            (
                SELECT 
                    sender_id, 
                    customer_id, 
                    message, 
                    timestamp
                FROM 
                    chats
                ORDER BY 
                    timestamp DESC
            ) m 
                ON u.user_id = m.customer_id
        WHERE 
            u.user_type = 'customer'
        GROUP BY 
            u.user_id
        ORDER BY 
            MAX(m.timestamp) DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return customers as JSON response
    header('Content-Type: application/json');
    echo json_encode($customers);
?>