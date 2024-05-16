<?php
// Include database connection
require_once '../database_connection.php';

// Function to update furniture details with explicit database connection parameter
function editFurniture($conn, $type, $color, $img_path, $works_id) {
    try {
        // Prepare update query using placeholders to prevent SQL injection
        $sql = "UPDATE works SET type = :type, color = :color, img_path = :img_path WHERE works_id = :works_id";
        $stmt = $conn->prepare($sql);
        
        // Bind parameters to the prepared statement
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':color', $color, PDO::PARAM_STR);
        $stmt->bindParam(':img_path', $img_path, PDO::PARAM_STR);
        $stmt->bindParam(':works_id', $works_id, PDO::PARAM_INT);
        
        // Execute the prepared statement
        $stmt->execute();
        
        // Check if any rows were affected
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo "Furniture updated successfully";
        } else {
            echo "No rows affected. Furniture update failed.";
        }
    } catch(PDOException $e) {
        echo "Error updating furniture: " . $e->getMessage();
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure that at least the works_id is set
    if (isset($_POST['editFurnitureId'])) {
        $works_id = $_POST['editFurnitureId'];
        $type = isset($_POST['editType']) ? $_POST['editType'] : null;
        $color = isset($_POST['editColor']) ? $_POST['editColor'] : null;
        $img_path = ''; // Default value for image path
        
        // Handle file upload if a new image file is provided
        if (isset($_FILES['editFile']) && $_FILES['editFile']['error'] === UPLOAD_ERR_OK) {
            $upload_directory = '../gallery/'; // Directory to store uploaded images
            $img_path = $upload_directory . basename($_FILES['editFile']['name']);
            
            if (move_uploaded_file($_FILES['editFile']['tmp_name'], $img_path)) {
                echo "File uploaded successfully.";
            } else {
                echo "Error uploading file.";
            }
        }
        
        // Check if any of the values (type, color, img_path) have been updated
        if ($type !== null || $color !== null || $img_path !== '') {
            // Call update function with provided data and database connection
            editFurniture($conn, $type, $color, $img_path, $works_id);
        } else {
            echo "No changes to update.";
        }
    } else {
        echo "Invalid input data. Update operation aborted.";
    }
}

// Redirect back to the CMS page after processing
header("Location: cms.php");
exit(); // Ensure that no further output is sent after the redirect
?>
