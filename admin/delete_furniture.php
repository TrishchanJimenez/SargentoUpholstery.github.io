<?php
// Include database connection
require_once '../database_connection.php';

// Function to delete furniture by works_id
function deleteFurniture($works_id) {
    
    
    try {
        // Prepare delete query using a placeholder to prevent SQL injection
        $sql = "DELETE FROM works WHERE works_id = :id";
        $stmt = $conn->prepare($sql);
        
        // Bind parameter to the prepared statement
        $stmt->bindParam(':id', $works_id, PDO::PARAM_INT);
        
        // Execute the prepared statement
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            echo "Furniture deleted successfully";
        } else {
            echo "No furniture deleted. Please check the ID.";
        }
    } catch(PDOException $e) {
        echo "Error deleting furniture: " . $e->getMessage();
    }
}

// Process AJAX request to delete furniture
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve works_id from the POST request
    $works_id = isset($_POST['works_id']) ? $_POST['works_id'] : null;
    
    // Call delete function if works_id is valid
    if ($works_id !== null) {
        deleteFurniture($works_id);
        exit(); // Stop script execution after processing AJAX request
    } else {
        echo "Invalid works_id. Deletion operation aborted.";
    }
}

// Redirect back to the CMS page after processing
header("Location: cms.php");
exit(); // Ensure that no further output is sent after the redirect
?>
