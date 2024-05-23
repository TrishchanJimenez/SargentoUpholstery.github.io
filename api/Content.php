<?php
    require '../database_connection.php';

    if(isset($_POST['content_id'])) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            echo "true";
            $id = $_POST['content_id'];
            $sql = "SELECT * FROM contents WHERE content_id = :content_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':content_id', $id);
            $stmt->execute();
            $content = $stmt->fetch(PDO::FETCH_ASSOC);
            // $targetFile = $_SERVER['DOCUMENT_ROOT'] . $content['image'];

            $sql = "UPDATE contents SET image = :image WHERE content_id = :content_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':content_id', $id);

            // Check if the file already exists
            if (!file_exists($_FILES['file']['name'])) {
                $stmt->bindParam(':image', $content['image']);
            } else {
                echo "test";
                $image = $_FILES['file'];
                $targetDirectory = '../websiteimages/';
                $targetFile = $targetDirectory . basename($image['name']);
                $uploadSuccess = move_uploaded_file($image['tmp_name'], $targetFile);

                if ($uploadSuccess) {
                    // Store a path relative to the root of your website in the database
                    $dbPath = '/websiteimages/' . basename($image['name']);
                    $stmt->bindParam(':image', $dbPath);
                } else {
                    // Handle error
                    echo json_encode(['error' => 'Failed to upload image.']);
                    return;
                }
            }
        } else {
            // Keep the existing image
        }
        if(isset($_POST['content_text'])) {
            $content_id = $_POST['content_id'];
            $content_text = $_POST['content_text'];
            if(strpos($content_text, "'") !== false) {
                $content_text = str_replace("'", "\\'", $content_text);
            }
            $sql = "UPDATE contents SET content_text = :content_text WHERE content_id = :content_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':content_text', $content_text);
            $stmt->bindParam(':content_id', $content_id);
            $stmt->execute();
        }
    }
?>