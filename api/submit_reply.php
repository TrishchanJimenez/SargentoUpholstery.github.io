<?php
// Include database connection
require "../database_connection.php";
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_SESSION['access_type'] === "admin") {
    // Get submitted data
    $review_id = $_POST['review_id'];
    $reply = $_POST['reply'];

    // $review_id = 2;
    // $reply = "Thank you";
    $currentTime = date("Y-m-d H:i:s");

    // Save the reply to the database
    $sql = "UPDATE reviews SET reply = ?, reply_date = ? WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$reply, $currentTime, $review_id]);

    echo json_encode([
        "reply" => $reply,
        "reply_date" => $currentTime
    ]);
}
?>
