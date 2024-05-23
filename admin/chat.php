<?php
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    include_once("../database_connection.php");

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message']) && isset($_POST['customer_id'])) {
        // Sanitize input to prevent SQL injection
        $message = $_POST['message'];
        $customer_id = $_POST['customer_id'];
        $sender_id = $_SESSION['user_id'];
        
        // Prepare and execute SQL query to insert message into database
        $sql = "INSERT INTO `chats` (`sender_id`, `customer_id`, `message`) VALUES (:sender_id, :customer_id, :message)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':message', $message);
        if ($stmt->execute()) {
            // Insertion successful, return success response
            http_response_code(200);
            exit();
        } else {
            // Error in insertion, return error response
            http_response_code(500);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="admin-chat__title">Admin Chat</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin/chat.css">
</head>
<body class="admin-chat__body">
    <div class="admin-chat">
        <!-- Sidebar -->
        <?php require('sidebar.php') ?>
        <!-- Main Content -->
        <div class="admin-chat__main-content">
            <!-- Header -->
            <div class="admin-chat__header">
                <p class="main-title">Admin Chat</p>
                <hr class="divider">
            </div>
            <div class="admin-chat__container">
                <!-- Customer List -->
                <div class="admin-chat__customer-list">
                    <h2 class="admin-chat__customer-heading">Customers</h2>
                    <hr class="admin-chat__customer-divider">
                    <ul class="admin-chat__customer-list-items">
                        <!-- Customer list items will be dynamically populated here -->
                    </ul>
                </div>
                <!-- Chat Window -->
                <div class="admin-chat__chat-window">
                    <h2 class="admin-chat__chat-heading">Chat with Customer</h2>
                    <div class="admin-chat__messages">
                        <!-- Messages will be dynamically populated here -->
                    </div>
                    <form id="message-form" class="admin-chat__message-form" method="post">
                        <input type="text" id="message-input" class="admin-chat__message-input" placeholder="Type a message...">
                        <button type="submit" class="admin-chat__send-button">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/admin/chat.js"></script>
</body>
</html>