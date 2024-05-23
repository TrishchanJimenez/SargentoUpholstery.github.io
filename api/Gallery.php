<?php
    require '../database_connection.php';

    function addImage() {
        global $conn;
        $category = $_POST['category'];
        $color = $_POST['color'];
        $image = $_FILES['file'];

        $sql = "INSERT INTO works (category, color, img_path) VALUES (:category, :color, :img_path)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':color', $color);

        // Code to handle image upload goes here
        $targetDirectory = '../websiteimages/galleryimages/';
        $targetFile = $targetDirectory . basename($image['name']);
        $uploadSuccess = move_uploaded_file($image['tmp_name'], $targetFile);

        if ($uploadSuccess) {
            // Store a path relative to the root of your website in the database
            $dbPath = '/websiteimages/galleryimages/' . basename($image['name']);
            $stmt->bindParam(':img_path', $dbPath);
            $stmt->execute();
            $uploadedImage = [
                'works_id' => $conn->lastInsertId(), // Get the ID of the last inserted row
                'category' => $category,
                'color' => $color,
                'img_path' => $dbPath
            ];
            echo json_encode($uploadedImage);
        } else {
            // Handle error
            echo json_encode(['error' => 'Failed to upload image.']);
        }
    }

    function deleteImage() {
        global $conn;
        $id = $_POST['id'];

        // Get the image path from the database
        $sql = "SELECT img_path FROM works WHERE works_id = :works_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':works_id', $id);
        $stmt->execute();
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        // Define the target file
        $targetFile = $_SERVER['DOCUMENT_ROOT'] . $image['img_path'];

        // Delete the record from the database
        $sql = "DELETE FROM works WHERE works_id = :works_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':works_id', $id);
        $stmt->execute();

        // Delete the image file from the server
        $deletedFile = unlink($targetFile);
        if ($deletedFile) {
            echo json_encode([
                'message' => 'Image and file deleted successfully.',
                'works_id' => $id
            ]);
        } else {
            echo json_encode(['error' => 'Failed to delete image file.']);
        }
    }

    function updateImage() {
        global $conn;
        $id = $_POST['works_id'];
        $category = $_POST['category'];
        $color = $_POST['color'];

        $sql = "SELECT * FROM works WHERE works_id = :works_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':works_id', $id);
        $stmt->execute();
        $work = $stmt->fetch(PDO::FETCH_ASSOC);
       
        $sql = "UPDATE works SET category = :category, color = :color, img_path = :img_path WHERE works_id = :works_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':works_id', $id);

        $dbPath = $work['img_path'];
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            // Delete the previous image
            $id = $_POST['works_id'];
            $targetFile = $_SERVER['DOCUMENT_ROOT'] . $work['img_path'];
            unlink($targetFile);

            // Upload the new image
            $image = $_FILES['file'];
            $targetDirectory = '../websiteimages/galleryimages/';
            $targetFile = $targetDirectory . basename($image['name']);
            $uploadSuccess = move_uploaded_file($image['tmp_name'], $targetFile);

            if ($uploadSuccess) {
                // Store a path relative to the root of your website in the database
                $dbPath = '/websiteimages/galleryimages/' . basename($image['name']);
                $stmt->bindParam(':img_path', $dbPath);
            } else {
                // Handle error
                echo json_encode(['error' => 'Failed to upload image.']);
                return;
            }
        } else {
            // Keep the existing image
            $stmt->bindParam(':img_path', $work['img_path']);
        }
        $stmt->execute();
        echo json_encode([
            'works_id' => $id,
            'category' => $category,
            'color' => $color,
            'img_path' => $dbPath
        ]);
    }

    function getImage() {
        global $conn;
        $id = $_GET['id'];
        $sql = "SELECT * FROM works WHERE works_id = :works_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':works_id', $id);
        $stmt->execute();
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($image);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        getImage();
    } else if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'insert':
                addImage();
                break;
            case 'update':
                updateImage();
                break;
            case 'delete':
                deleteImage();
                break;
        }
    }
?>