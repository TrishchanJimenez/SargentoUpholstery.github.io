<?php
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    include_once("../database_connection.php");

    // Check if form is submitted
    try {
        // Sanitize input to prevent SQL injection
        $message = htmlspecialchars($_POST['message']);
        $customer_id = htmlspecialchars($_POST['customer_id']);
        $sender_id = $_SESSION['user_id'];

        // Prepare and execute SQL query to insert message into database
        $sql = "INSERT INTO `chats` (`sender_id`, `customer_id`, `message`) VALUES (:sender_id, :customer_id, :message)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        // Insertion successful, return success response
        http_response_code(200);
        exit(); // Exit to prevent further execution
    } catch (PDOException $e) {
        // Error in insertion, return error response
        http_response_code(500);
        exit(); // Exit to prevent further execution
    }
?>