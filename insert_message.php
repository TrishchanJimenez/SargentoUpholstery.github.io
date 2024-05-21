<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once("database_connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    // Sanitize input to prevent SQL injection
    $message = htmlspecialchars($_POST['message']);
    $user_id = $_SESSION['user_id'];

    // Prepare and execute SQL query to insert message into database
    $sql = "INSERT INTO `chats` (`sender_id`, `customer_id`, `message`) VALUES (:user_id, :user_id, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':message', $message);
    if ($stmt->execute()) {
        // Insertion successful, return success response
        http_response_code(200);
        exit(); // Exit to prevent further execution
    } else {
        // Error in insertion, return error response
        http_response_code(500);
        exit(); // Exit to prevent further execution
    }
} else {
    // Invalid request
    http_response_code(400);
    exit(); // Exit to prevent further execution
}
?>