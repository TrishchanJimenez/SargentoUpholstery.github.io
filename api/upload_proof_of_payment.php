<?php
    include_once('../alert.php');
    if (isset($_POST['upload_payment'])) {
        $order_id = $_POST['order_id'];
        $uploadType = $_POST['upload_type'];
        $targetDir = '../uploadedImages/paymentImages/';
        $targetFile = $targetDir . basename($_FILES['payment_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate the image file
        $check = getimagesize($_FILES['payment_image']['tmp_name']);
        if ($check !== false) {
            // Check if file already exists
            if (!file_exists($targetFile)) {
                // Check file size (limit to 5MB)
                if ($_FILES['payment_image']['size'] <= 5000000) {
                    // Allow certain file formats
                    if ($imageFileType === 'jpg' || $imageFileType === 'png' || $imageFileType === 'jpeg') {
                        if (move_uploaded_file($_FILES['payment_image']['tmp_name'], $targetFile)) {
                            // Update database with the image path
                            try {
                                if ($uploadType === 'downpayment') {
                                    $stmt = $conn->prepare("UPDATE payment SET downpayment_img = :img WHERE order_id = :order_id");
                                } elseif ($uploadType === 'fullpayment') {
                                    $stmt = $conn->prepare("UPDATE payment SET fullpayment_img = :img WHERE order_id = :order_id");
                                }
                                $stmt->bindParam(':img', $targetFile);
                                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                                $stmt->execute();
                                sendAlert("success", "The file " . htmlspecialchars(basename($_FILES['payment_image']['name'])) . " has been uploaded.");
                            } catch (PDOException $e) {
                                echo "Error updating database: " . $e->getMessage();
                            }
                        } else {
                            sendAlert("error", "Sorry, there was an error uploading your file.");
                        }
                    } else {
                        sendAlert("error", "Sorry, only JPG, JPEG & PNG files are allowed.");
                    }
                } else {
                    sendAlert("error", "Sorry, your file is too large.");
                }
            } else {
                sendAlert("error", "Sorry, file already exists.");
            }
        } else {
            sendAlert("error", "File is not an image.");
        }
    }
?>