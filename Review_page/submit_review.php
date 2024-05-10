<?php
// Include database connection
include_once "db_connection.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $rating = $_POST["rating"];
    $review = $_POST["review"];
    
    // Handle avatar upload
    $avatarPath = "uploads/avatars/" . $_FILES["avatar"]["name"];
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatarPath);

    // Handle additional images upload
    $imagePaths = [];
    if (!empty($_FILES["images"]["name"])) {
        foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
            $imagePath = "uploads/images/" . $_FILES["images"]["name"][$key];
            move_uploaded_file($tmp_name, $imagePath);
            $imagePaths[] = $imagePath;
        }
    }

    // Insert review into database
    $sql = "INSERT INTO reviews (username, rating, review, avatar, images) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisss", $username, $rating, $review, $avatarPath, implode(",", $imagePaths));

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
