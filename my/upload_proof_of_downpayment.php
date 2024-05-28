<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<div class="upod">
    <form class="form" method="post" enctype="multipart/form-data">
        <div class="form__wrapper form__wrapper--upload">
            <h1 class="form__title">Upload Proof of Downpayment</h1>

            <label class="form__label" for="payment_method">Payment Method</label>
            <select class="form__select" name="payment_method" id="payment_method">
                <option class="form__option" value="gcash">GCash</option>
                <option class="form__option" value="paymaya">Paymaya</option>
                <option class="form__option" value="cash">Cash</option>
            </select>

            <label class="form__label" for="proof_upload">Upload File</label>
            <input class="form__input" type="file" id="proof_upload" name="proof_upload" accept="image/*,application/pdf" required>

            <p class="form__note">Accepted formats: JPEG, PNG, PDF. Maximum size: 5MB.</p>
        </div>
        <input class="form__submit" type="submit" name="submit--upod" value="Submit Proof">
    </form>
</div>

<?php
    require_once('../database_connection.php');
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit--upod"])) {
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

        $payment_method = $_POST['payment_method'];

        // Check if the file type is allowed
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Check the file size
            if ($_FILES["proof_upload"]["size"] <= $maxFileSize) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["proof_upload"]["tmp_name"], $targetFilePath)) {
                    try {
                        // Write the query
                        $query = "
                            UPDATE 
                                `payment` 
                            SET 
                                `downpayment_method` = :payment_method, 
                                `downpayment_img` = :targetFilePath,
                                `downpayment_verification_status` = 'waiting_for_verification'
                            WHERE 
                                `order_id` = :order_id";
                        // Prepare the query
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
                        $stmt->bindParam(':targetFilePath', $dbpath, PDO::PARAM_STR);
                        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                        // Execute the query
                        $stmt->execute();
                        echo '<script type="text/javascript"> alert("You have successfully uploaded a proof of downpayment.") </script>'; 
                    } catch (PDOException $e) {
                        // Handle database error
                        echo "<script>console.log(" . $e->getMessage() . ")</script>";
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
    }
?>