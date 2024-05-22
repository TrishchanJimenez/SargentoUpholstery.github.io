<?php
    // Include database connection
    include_once("../database_connection.php");

    // Check if customer_id is provided
    if (!isset($_GET['customer_id'])) {
        http_response_code(400);
        exit(); // Exit to prevent further execution
    }

    // Sanitize input
    $customer_id = htmlspecialchars($_GET['customer_id']);

    // Fetch messages for the specified customer
    $query = "
        SELECT 
            sender_id, 
            message, 
            timestamp
        FROM 
            chats
        WHERE 
            (sender_id = :customer_id OR customer_id = :customer_id)
        ORDER BY 
            timestamp 
                ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return messages as JSON response
    header('Content-Type: application/json');
    echo json_encode($messages);
?>