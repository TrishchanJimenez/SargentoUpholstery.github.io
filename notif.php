<?php
// Include database connection file
include_once("database_connection.php");

function createNotif($user_id, $notif_msg) {
    global $conn;

    // Insert new notification into the database
    $query = "INSERT INTO notifs (user_id, notif_msg, created_at, is_read) VALUES (:user_id, :notif_msg, NOW(), 0)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':notif_msg', $notif_msg, PDO::PARAM_STR);
    if ($stmt->execute()) {
        return true; // Notification created successfully
    } else {
        return false; // Failed to create notification
    }
}
?>