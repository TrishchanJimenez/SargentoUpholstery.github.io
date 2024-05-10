<?php
// Include database connection
include_once "db_connection.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get submitted data
    $review_id = $_POST['review_id'];
    $reply = $_POST['reply'];

    // Save the reply to the database
    $sql = "UPDATE reviews SET reply = ? WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $reply, $review_id);

    // Execute the update query
    if ($stmt->execute()) {
        // Query executed successfully, retrieve updated review data
        $updated_review = [];

        // Select the updated review from the database
        $select_sql = "SELECT * FROM reviews WHERE review_id = ?";
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->bind_param("i", $review_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $updated_review = $result->fetch_assoc();
        }

        // Close database connection
        $conn->close();

        // Return JSON response with the updated review data
        header('Content-Type: application/json');
        echo json_encode($updated_review);
        exit;
    } else {
        // Error occurred while updating the review
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to update review']);
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}
?>
