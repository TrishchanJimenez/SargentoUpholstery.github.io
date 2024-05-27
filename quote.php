<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit;
    } else {
        $af_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $af_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : '';
        $af_contact = isset($_SESSION['contact_number']) ? $_SESSION['contact_number'] : '';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/quote.css">
</head>

<body>
    <?php 
        require_once('database_connection.php');
        // Get all addresses of the user
        $query = "SELECT * FROM `addresses` WHERE `user_id` = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $addresses = $stmt->fetchAll(); 
    ?>
    <?php
        // APIs
        require_once("alert.php");
        include_once("api/CheckAddress.php");
    ?>
    <?php 
        require_once('header.php');
        $needs_cta = false;
        require_once('intro.php');
    ?>
    <div class="quote-page__content">
        <div class="quote">
            <form class="quote-form" method="POST" enctype="multipart/form-data">
                <div class="quote-form__heading">
                    <h1 class="quote-form__title">Quotation Form</h1>
                    <p class="quote-form__subtext">
                        Request a quote or an estimate price from us. 
                        Simply fill in the necessary details & submit the form, and we will review your quote as soon as possible.<br>
                        <br>
                        *All fields with an asterisk are required.
                    </p>
                </div>
                <!-- Quote Form mismo -->
                <div class="quote-form__wrapper">
                    <!-- Personal Information Section -->
                    <div class="quote-form__display" id="personal-information">
                        <h2 class="quote-form__header">Personal Information</h2>
                        <p class="quote-form__description">Please ensure that your personal information is correct.</p>
                        
                        <div class="quote-form__output-container">
                            <label class="quote-form__label" for="name">Full Name *</label> 
                            <input class="quote-form__output quote-form__output--text" type="text" id="name" name="name" value="<?= $af_name ?>" required readonly>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="user_address">Address *</label> 
                            <input class="quote-form__output quote-form__output--text" type="text" id="user_address" name="user_address" value="<?= $af_address ?>" required readonly>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="contact_no">Contact Number *</label> 
                            <div class="quote-form__input-text--contact">
                                <!-- <select class="quote-form__output" id="country-code" name="country-code" required>
                                    <option value="+63">+63</option>
                                </select> -->
                                <input class="quote-form__input quote-form__input--text" type="tel" id="contact_no" name="contact_no" pattern="[0-9]{11}" value="<?= $af_contact ?>" required readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Furniture Details Section -->
                    <div class="quote-form__section" id="details">
                        <h2 class="quote-form__header">Furniture Details</h2>
                        <p class="quote-form__description">Please provide information about the furniture.</p>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="furniture_type">Furniture Type *</label> 
                            <input class="quote-form__input quote-form__input--text" type="text" id="furniture_type" name="furniture_type" placeholder="E.g. sofa, dining seats, bed" required>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="service_type">Service Type *</label> 
                            <select class="quote-form__input quote-form__input--select" name="service_type" id="service_type" required>
                                <option value="repair">Repair</option>
                                <option value="mto">Made-to-Order</option>
                            </select>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="description">Furniture Description *</label> 
                            <textarea class="quote-form__input quote-form__input--textarea" id="description" name="description" placeholder="Please describe the furniture in detail." required></textarea>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--file">
                            <label class="quote-form__label" for="ref_img">Reference Image</label> 
                            <input class="quote-form__input quote-form__input--file" type="file" id="ref_img" name="ref_img" accept="images/*">
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="quantity">Quantity *</label> 
                            <input class="quote-form__input quote-form__input--number" type="number" id="quantity" name="quantity" value="1" min="1" max="50" required>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="enable_customization" name="enable_customization">
                            <label class="quote-form__label" for="enable_customization">Add Customization</label>
                        </div>
                    </div>

                    <!-- Legal Agreements Section -->
                    <div class="quote-form__section" id="legal-agreements">
                        <h2 class="quote-form__header">Legal Agreements</h2>
                        <p class="quote-form__description">Please read our terms and conditions & data privacy notice.</p>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="legal-terms" name="legal-terms" required>
                            <label class="quote-form__label" for="legal-terms">I have read and agree to the <a href="/legal-agreements#terms-and-conditions" target="_blank">Terms and Conditions</a></label>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="legal-data" name="legal-data" required>
                            <label class="quote-form__label" for="legal-data">I have read and agree to the <a href="/legal-agreements#data-privacy" target="_blank">Data Privacy Notice</a></label>
                        </div>

                        <!-- Submit Button -->
                        <div class="quote-form__heading-actions">
                            <button class="quote-form__submit" type="submit">Submit Request</button>
                        </div>
                    </div>

                    <!-- Customization Section -->
                    <div class="quote-form__section" id="customization">
                        <h2 class="quote-form__header">Furniture Customization</h2>
                        <p class="quote-form__description">Customize the furniture. If you do not wish to customize a certain field, simply leave it blank.</p>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <label class="quote-form__label quote-form__label--customization" for="dimensions">Specify Dimensions (in meters)</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="dimensions" name="dimensions" placeholder="Length x Width x Height">
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <label class="quote-form__label quote-form__label--customization" for="materials">Specify Materials</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="materials" name="materials" placeholder="E.g. wood, plastic, metal">
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <label class="quote-form__label quote-form__label--customization" for="fabric">Specify Fabric</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="fabric" name="fabric" placeholder="E.g cotton, linen, leather">
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <label class="quote-form__label quote-form__label--customization" for="color">Specify Color</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="color" name="color" placeholder="E.g black, blue, red">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php 
        require_once('outro.php');
        require_once('footer.php');
    ?>
    <script src="js/globals.js"></script>
    <script src="js/quote.js"></script>
</body>
</html>

<?php
    require_once('database_connection.php');

    // Sanitize inputs
    function sanitizeInputs($data) {
        foreach($data as $key => $value) {
            // Check if the value is an array
            if(is_array($value)) {
                // If it's an array, recursively call sanitizeInputs
                $data[$key] = sanitizeInputs($value);
            } else {
                // If it's not an array, sanitize the value
                $data[$key] = htmlspecialchars(strip_tags($value));
            }
        }
        return $data;
    }

    

    // If form is completed
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Call the sanitizeInputs function on $_POST
        $_POST = sanitizeInputs($_POST);
        // Extract sanitized $_POST variables into separate variables
        extract($_POST);

        // ----- If customization is selected, insert into quotes_custom table ----- //
        if ($_POST['enable_customization'] == 'on') {
            try {
                $query = "
                    INSERT INTO
                        `quote_customs` (
                            `dimensions`,
                            `materials`,
                            `fabric`,
                            `color`
                        )
                    VALUES (
                        :dimensions,
                        :materials,
                        :fabric,
                        :color
                    )
                ";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':dimensions', $dimensions);
                $stmt->bindParam(':materials', $materials);
                $stmt->bindParam(':fabric', $fabric);
                $stmt->bindParam(':color', $color);
                $stmt->execute();
        
                $custom_id = $conn->lastInsertId();
            } catch (PDOException $e) {
                echo "<script>alert(" . $e->getMessage() . ")</script>";
                sendAlert("warning", $e->getMessage());
            }
        }

        // ----- Insert into quotes table and create notif ----- //
        try {
            $query = "
                INSERT INTO
                    `quotes` (
                        `customer_id`,
                        `furniture_type`,
                        `service_type`,
                        `description`,
                        `ref_img_path`,
                        `quantity`,
                        `custom_id`
                    )
                VALUES (
                    :customer_id,
                    :furniture_type,
                    :service_type,
                    :description,
                    :ref_img_path,
                    :quantity,
                    :custom_id
                )
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':customer_id', $_SESSION['user_id']);
            $stmt->bindParam(':furniture_type', $furniture_type);
            $stmt->bindParam(':service_type', $service_type);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':ref_img_path', $ref_img_path);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':custom_id', $custom_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "<script>alert(" . $e->getMessage() . ")</script>";
            sendAlert("warning", $e->getMessage());
        }

        // ----- Rename and save uploaded image ----- //
        // Get the quote ID after insertion
        $quote_id = $conn->lastInsertId();

        // Get the customer's name
        $customer_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';

        

        // Check if a file was uploaded
        $ref_img_path = null;
        $uploadOk = 1;
        if (isset($_FILES["ref_img"]) && $_FILES["ref_img"]["error"] == 0) {
            $target_dir = "uploadedImages/referenceImages/";
            $target_file = $target_dir . basename($_FILES["ref_img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if the file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["ref_img"]["size"] > 5000000) { // Adjust this size limit as needed
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow only certain file formats
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowed_extensions)) {
                echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk) {
                if (move_uploaded_file($_FILES["ref_img"]["tmp_name"], $target_file)) {
                    $ref_img_path = $target_file;
                    echo "The file ". basename( $_FILES["ref_img"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    $uploadOk = 0;
                }
            }
        } else {
            echo "File upload is required.";
            $uploadOk = 0;
        }



        try {
            // Create a new notification message
            $notif_msg = "You have successfully placed a quote request. Please await confirmation of order."; // Customize the message as needed
            // Call the createNotif function
            if (createNotif($_SESSION['user_id'], $notif_msg, "/my/user_orders.php")) {
                // Notification created successfully
                echo "Notification created successfully";
                sendAlert("success", "You have successfully placed a quote request. Please await confirmation of order.");
            } else {
                // Failed to create notification
                echo "Failed to create notification";
            }
        } catch (PDOException $e) {
            echo "<script>alert(" . $e->getMessage() . ")</script>";
            sendAlert("warning", $e->getMessage());
        }
    }
?>