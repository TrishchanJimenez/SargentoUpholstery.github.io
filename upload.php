<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sargento_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['order_id'])) {
    $uploadDir = 'uploadedImages/fullpaymentProof';
    $orderId = intval($_POST['order_id']);
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare('UPDATE payment SET fullpayment_img = ? WHERE order_id = ?');
        $stmt->bind_param('si', $uploadFile, $orderId);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'File uploaded and path stored in database successfully.';
        } else {
            echo 'Failed to store the file path in the database.';
        }
        $stmt->close();
    } else {
        echo 'Failed to upload the file.';
    }
} else {
    echo 'No file uploaded.';
}

// Close connection
$conn->close();
?>
