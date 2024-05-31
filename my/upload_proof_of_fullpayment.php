<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!-- Modal for Upload Proof of Fullpayment -->
<div class="modal" id="upofModal">
    <div class="modal__content">
        <span class="modal__close" id="closeUPOF">&times;</span>
        <div class="form__wrapper form__wrapper--upload">
            <h1 class="form__title">Upload Proof of Fullpayment</h1>
            <form id="upofForm" class="form" method="post" enctype="multipart/form-data">
                <label class="form__label" for="payment_method">Payment Method</label>
                <select class="form__select" name="payment_method" id="payment_method">
                    <option class="form__option" value="gcash">GCash</option>
                    <option class="form__option" value="paymaya">Paymaya</option>
                    <option class="form__option" value="cash">Cash</option>
                </select>

                <label class="form__label" for="account_holder">Account Holder Name</label>
                <input class="form__input" type="text" id="account_holder" name="account_holder" required>

                <label class="form__label" for="amount">Amount</label>
                <input class="form__input" type="number" id="amount" name="amount" required>

                <label class="form__label" for="reference_no">Reference No. (For cash payments, enter N/A instead)</label>
                <input class="form__input" type="text" id="reference_no" name="reference_no" required>

                <label class="form__label" for="proof_upload">Upload File</label>
                <input class="form__input" type="file" id="proof_upload" name="proof_upload" accept="image/*,application/pdf" required>

                <p class="form__note">Accepted formats: JPEG, PNG, PDF. Maximum size: 5MB.</p>
                <input class="form__submit" type="submit" name="submit--upof" value="Submit Proof">
            </form>
        </div>
    </div>
</div>

<style>
    /* ---------- Modal ---------- */

    /* General modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
        justify-content: center; /* Center the modal content horizontally */
        align-items: center; /* Center the modal content vertically */
        font-family: "Inter", sans-serif;
    }

    .modal__content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: fit-content;
        max-width: 80%; /* Optional: Limit modal width */
        height: fit-content;
        max-height: 80%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative; /* Required for close button positioning */
        overflow: auto;
    }

    /* Close button styles */
    .modal__close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
    }

    .modal__close:hover,
    .modal__close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    /* General form styles */
    .form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        margin-top: 5vmin;
    }

    .form__wrapper {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
    }

    .form__title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .form__label {
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
    }

    .form__select, 
    .form__input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .form__select {
        appearance: none;
        background: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%23333" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 9l-7 7-7-7"></path></svg>') no-repeat right 10px center;
        background-size: 16px 16px;
    }

    .form__option {
        padding: 10px;
        font-size: 16px;
    }

    .form__input {
        box-sizing: border-box;
    }

    .form__submit {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form__submit:hover {
        background-color: #0056b3;
    }
</style>

<?php
    require_once('../database_connection.php');
    include_once('../notif.php');
    include_once('../alert.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit--upof"])) {
        // Define the target directory for uploads
        $targetDir = "../uploadedImages/paymentImages";
        // Create the uploads directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get the uploaded file information
        $fileName = basename($_FILES["fullpay_img"]["name"]);
        $targetFilePath = $targetDir . '/' . $fileName;
        $dbpath = "uploadedImages/paymentImages/" . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Set the allowed file types and maximum file size (5MB)
        $allowedTypes = array('jpg', 'jpeg', 'png');
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

        $fullpay_method = $_POST['fullpay_method'];
        $fullpay_account_name = htmlspecialchars(trim($_POST['fullpay_account_name']));
        $fullpay_amount = $_POST['fullpay_amount'];
        $fullpay_ref_no = htmlspecialchars(trim($_POST['fullpay_ref_no']));

        // Check if the file type is allowed
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Check the file size
            if ($_FILES["fullpay_img"]["size"] <= $maxFileSize) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["fullpay_img"]["tmp_name"], $targetFilePath)) {
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
                        if($stmt->execute()) {
                            sendAlert("success", "You have successfully uploaded a proof of downpayment for this order.");
                            createNotif($_SESSION['user_id'], 'You have uploaded a proof of downpayment for Order #' . $order_id . '.', '/my/orders.php?order_id=' . $order_id);
                        } else {
                            echo "<script> console.log('Failed to execute query in upload_proof_of_fullpayment.php') </script>";
                        }
                    } catch (PDOException $e) {
                        echo "<script> console.log(" . $e->getMessage() . ") </script>";
                    }
                } else {
                    sendAlert("error", "Sorry, there was an error uploading the file.");
                }
            } else {
                sendAlert("warming", "File is too large. Maximum size is 5MB.");
            }
        } else {
            sendAlert("warming", "Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
        }
    }
?>