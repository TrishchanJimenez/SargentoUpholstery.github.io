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
        JOIN 
            chats c ON c.customer_id = u.user_id OR c.sender_id = u.user_id
        WHERE 
            u.user_type = 'customer'
        GROUP BY 
            u.user_id
        HAVING 
            COUNT(c.chat_id) >= 1
        ORDER BY 
            MAX(c.timestamp) DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return customers as JSON response
    header('Content-Type: application/json');
    echo json_encode($customers);
?>
