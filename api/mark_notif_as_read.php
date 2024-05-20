<?php
// Include database connection file
include_once("../database_connection.php");

// Check if notif_id is set and not empty
if (isset($_POST['notif_id']) && !empty($_POST['notif_id'])) {
    // Sanitize the input
    $notif_id = filter_var($_POST['notif_id'], FILTER_SANITIZE_NUMBER_INT);

    // Update is_read status in the database
    $query = "UPDATE notifs SET is_read = 1 WHERE notif_id = :notif_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':notif_id', $notif_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        // Return success response
        http_response_code(200);
        echo "Notification marked as read successfully";
        exit;
    } else {
        // Return error response
        http_response_code(500);
        echo "Failed to mark notification as read";
        exit;
    }
} else {
    // Return error response if notif_id is not set or empty
    http_response_code(400);
    echo "Invalid input";
    exit;
}
?>