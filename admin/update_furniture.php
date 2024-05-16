<?php
// update_furniture.php

// Include database connection function
require '../database_connection.php';

// Function to update furniture details
function updateFurniture($id, $category, $color, $img_path) {
    $conn = connectDB();
    
    // Escape inputs to prevent SQL injection
    $category = mysqli_real_escape_string($conn, $category);
    $color = mysqli_real_escape_string($conn, $color);
    
    // Perform update query
    $sql = "UPDATE furniture SET category = '$category', color = '$color', img_path = '$img_path' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "Furniture updated successfully";
    } else {
        echo "Error updating furniture: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['editFurnitureId'];
    $category = $_POST['editCategory'];
    $color = $_POST['editColor'];
    $img_path = ''; // Handle image upload and set $img_path accordingly

    // Call update function
    updateFurniture($id, $category, $color, $img_path);
}
?>
