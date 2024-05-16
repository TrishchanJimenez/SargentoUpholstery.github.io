<?php
require '../database_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $color = $_POST['color'];

    $imagePaths = [];

    if (!empty($_FILES['file']['name'][0])) {
        foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['file']['name'][$key];
            $fileTmpPath = $_FILES['file']['tmp_name'][$key];
            $uploadDir = '../gallery/';
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $imagePaths[] = $uploadPath;
            } else {
                echo "Failed to move uploaded file '$fileName' to gallery directory.<br>";
            }
        }

        if (!empty($imagePaths)) {
            $query = "INSERT INTO Works (category, color, img_path) VALUES (:category, :color, :img_path)";
            $stmt = $conn->prepare($query);

            foreach ($imagePaths as $imgPath) {
                $stmt->bindParam(':category', $category);
                $stmt->bindParam(':color', $color);
                $stmt->bindParam(':img_path', $imgPath);

                if ($stmt->execute()) {
                    echo "Furniture added successfully!";
                } else {
                    echo "Failed to insert furniture data into database.<br>";
                }
            }

            // Redirect back to cms.php after successful insertion
            header("Location: cms.php");
            exit(); // Ensure that no further output is sent
        } else {
            echo "No image uploaded.<br>";
        }
    } else {
        echo "No file uploaded or an error occurred during upload.<br>";
    }

    $stmt = null;
} else {
    echo "Invalid request method.<br>";
}
?>
