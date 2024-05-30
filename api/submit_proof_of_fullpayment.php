<?php
    require_once('../database_connection.php');
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit--upof"])) {
        $response = ["success" => false];
        // Define the target directory for uploads
        $targetDir = "../uploadedImages/paymentImages";
        
        // Create the uploads directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get the uploaded file information
        $fileName = basename($_FILES["proof_upload"]["name"]);
        $targetFilePath = $targetDir . '/' . $fileName;
        $dbpath = "uploadedImages/paymentImages/" . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Set the allowed file types and maximum file size (5MB)
        $allowedTypes = array('jpg', 'jpeg', 'png');
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

        $fullpay_method = $_POST['payment_method'];
        $fullpay_account_name = htmlspecialchars(trim($_POST['account_holder']));
        $fullpay_amount = $_POST['amount'];
        $fullpay_ref_no = htmlspecialchars(trim($_POST['reference_no']));

        // Check if the file type is allowed
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Check the file size
            if ($_FILES["proof_upload"]["size"] <= $maxFileSize) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["proof_upload"]["tmp_name"], $targetFilePath)) {
                    try {
                        // Write the query
                        $query = "
                            INSERT INTO
                                `fullpayment` (
                                    order_id,
                                    fullpay_method,
                                    fullpay_img_path,
                                    fullpay_account_name,
                                    fullpay_amount,
                                    fullpay_ref_no,
                                    fullpay_verification_status
                                )
                            VALUES (
                                :order_id,
                                :fullpay_method,
                                :fullpay_img_path,
                                :fullpay_account_name,
                                :fullpay_amount,
                                :fullpay_ref_no,
                                'waiting_for_verification'
                            )
                        ";
                        // Prepare the query
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                        $stmt->bindParam(':fullpay_method', $fullpay_method, PDO::PARAM_STR);
                        $stmt->bindParam(':fullpay_img_path', $dbpath, PDO::PARAM_STR);
                        $stmt->bindParam(':fullpay_account_name', $fullpay_account_name, PDO::PARAM_STR);
                        $stmt->bindParam(':fullpay_amount', $fullpay_amount);
                        $stmt->bindParam(':fullpay_ref_no', $fullpay_ref_no, PDO::PARAM_STR);
                        // Execute the query
                        if($stmt->execute()) 
                            echo '<script type="text/javascript">
                                alert("You have successfully uploaded a proof of fullpayment.")
                            </script>';

                        $response["success"] = true;
                        $response["message"] = "You have successfully set the address of an order.";
                    } catch (PDOException $e) {
                        // Handle database error
                        $response["error"] = $e->getMessage();
                        echo "<script>console.log('Sorry, there was an error uploading your file.')</script>";
                    }
                } else {
                    echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
                }
            } else {
                echo "<script>alert('Sorry, your file is too large. Maximum file size is 5MB.')</script>";
            }
        } else {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG, and PDF files are allowed.')</script>";
        }

        echo json_encode($response);
    }
?>