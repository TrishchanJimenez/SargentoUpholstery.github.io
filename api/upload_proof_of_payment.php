<?php
    
    include_once('../alert.php');
    if (isset($_POST['upload_payment'])) {
        $order_id = $_POST['order_id'];
        $uploadType = $_POST['upload_type'];
        $paymentMethod = $_POST['payment_method'];
        $targetDir = '../uploadedImages/paymentImages/';
        $targetFile = $targetDir . basename($_FILES['payment_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
        // Validate the image file
        $check = getimagesize($_FILES['payment_image']['tmp_name']);
        if ($check !== false) {
            // Check file size (limit to 5MB)
            if ($_FILES['payment_image']['size'] <= 5000000) {
                // Allow certain file formats
                if ($imageFileType === 'jpg' || $imageFileType === 'png' || $imageFileType === 'jpeg' || $imageFileType === 'gif') {
                    if (move_uploaded_file($_FILES['payment_image']['tmp_name'], $targetFile)) {
                        // Update database with the image path and payment method
                        try {
                            if ($uploadType === 'downpayment') {
                                $stmt = $conn->prepare("UPDATE payment SET downpayment_img = :img, downpayment_method = :method, downpayment_verification_status = 'waiting_for_verification' WHERE order_id = :order_id");
                            } elseif ($uploadType === 'fullpayment') {
                                $stmt = $conn->prepare("UPDATE payment SET fullpayment_img = :img, fullpayment_method = :method, fullpayment_verification_status = 'waiting_for_verification' WHERE order_id = :order_id");
                            }
                            $stmt->bindParam(':img', $targetFile);
                            $stmt->bindParam(':method', $paymentMethod);
                            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                            $stmt->execute();
                            sendAlert("success", "The file " . htmlspecialchars(basename($_FILES['payment_image']['name'])) . " has been uploaded.");
                        } catch (PDOException $e) {
                            echo "Error updating database: " . $e->getMessage();
                        }
                    } else {
                        sendAlert("warning", "Sorry, there was an error uploading your file.");
                    }
                } else {
                    sendAlert("warning", "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }
            } else {
                sendAlert("warning", "Sorry, your file is too large.");
            }
        } else {
            sendAlert("warning", "File is not an image.");
        }
    }
?>