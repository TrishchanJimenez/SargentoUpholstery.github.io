<?php
    // Include database connection
    include_once("../database_connection.php");

    // Fetch customers who have sent or received messages
    $query = "
        SELECT 
            u.user_id, 
            u.name AS customer_name
        FROM 
            users u
        WHERE 
            u.user_type = 'customer' AND 
            EXISTS (
                SELECT 1
                FROM chats c
                WHERE c.customer_id = u.user_id OR c.sender_id = u.user_id
            )
        ORDER BY 
            u.name ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return customers as JSON response
    header('Content-Type: application/json');
    echo json_encode($customers);
?>
