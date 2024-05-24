<?php
// Include database connection
require '../database_connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST["order_id"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];
    
    // Handle additional images upload
    $imagePaths = [];
    if (!empty($_FILES["images"]["name"])) {
        foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
            $imagePath = "../uploadedImages/reviewImages/" . $_FILES["images"]["name"][$key];
            move_uploaded_file($tmp_name, $imagePath);

            $dbPath = '/uploadedImages/reviewImages/' . basename($_FILES["images"]["name"][$key]);
            $imagePaths[] = $dbPath;
        }
    }

    // Insert review into database
    $sql = "INSERT INTO reviews (order_id, rating, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $order_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $rating, PDO::PARAM_INT);
    $stmt->bindParam(3, $comment, PDO::PARAM_STR);
    $stmt->execute();
    $review_id = $conn->lastInsertId();

    $sql = "INSERT INTO review_images (review_id, path) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($imagePaths as $imagePath) {
        $stmt->bindParam(1, $review_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $imagePath, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Close statement and database connection
    $stmt->closeCursor();
    $stmt = null;
    $conn = null;
}
?>
