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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                        <h2 class="quote-form__header">Request Details</h2>
                        <p class="quote-form__description">Please provide information about your quotation request.</p>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="service_type">Service Type *</label> 
                            <select class="quote-form__input quote-form__input--select" name="service_type" id="service_type" required onchange="displayAppropriateInput(event)">
                                <option value="repair">Repair</option>
                                <option value="mto">Made-to-Order</option>
                            </select>
                        </div>
                    </div>
                    <div class="quote-form__furniture-container">
                        <!-- <div class="quote-form__section quote-form__furniture-item">
                            <h2 class="quote-form__header">Furniture Detail<i class="fa fa-close remove-item" onclick="removeItem(this.closest(' .quote-form__furniture-item'))"></i></h2>
                            <div class="quote-form_furniture-item_main-detail">
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="furniture_type">Furniture Type *</label> 
                                    <input class="quote-form__input quote-form__input--text required" type="text" id="furniture_type" name="furniture_type[]" placeholder="E.g. sofa, dining seats, bed" required cols="25" disabled>
                                </div>

                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="quantity">Quantity *</label> 
                                    <input class="quote-form__input quote-form__input--number required" type="number" id="quantity" name="quantity[]" value="1" min="1" max="50" required disabled>
                                </div>
        
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="description">Furniture Description *</label> 
                                    <textarea class="quote-form__input quote-form__input--textarea required" id="description" name="description[]" placeholder="Please describe the furniture in detail." required disabled></textarea>
                                </div>
        
                                <div class="quote-form__input-container quote-form__input-container--file">
                                    <label class="quote-form__label" for="ref_img">Reference Image</label> 
                                    <input class="quote-form__input quote-form__input--file" type="file" id="ref_img" name="ref_img[]" accept="images/*">
                                </div>
                            </div>
                            <div class="quote-form_furniture-item_sub-detail">
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="dimensions">Specify Dimensions (in meters)</label>
                                    <input class="quote-form__input quote-form__input--text" type="text" id="dimensions" name="dimensions[]" placeholder="Length x Width x Height">
                                </div>
        
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="materials">Specify Materials</label>
                                    <input class="quote-form__input quote-form__input--text" type="text" id="materials" name="materials[]" placeholder="E.g. wood, plastic, metal">
                                </div>
        
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="fabric">Specify Fabric</label>
                                    <input class="quote-form__input quote-form__input--text" type="text" id="fabric" name="fabric[]" placeholder="E.g cotton, linen, leather">
                                </div>
        
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="color">Specify Color</label>
                                    <input class="quote-form__input quote-form__input--text" type="text" id="color" name="color[]" placeholder="E.g black, blue, red">
                                </div>
                            </div>
                        </div> -->
                        <div class="quote-form__add-item" id="add-item" onclick="addItem()">ADD ANOTHER TYPE</div>
                    </div>
                    <div class="quote-form__furniture-repair-container">
                        <div class="quote-form__section quote-form__furniture-item-repair">
                            <h2 class="quote-form__header">Repair Detail<i class="fa fa-close remove-item" onclick="removeRepairItem(this.closest(' .quote-form__furniture-item-repair'))"></i></h2>
                            <div class="quote-form_furniture-item_main-detail">
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="furniture_type">Furniture Type *</label> 
                                    <input class="quote-form__input quote-form__input--text required" type="text" id="furniture_type" name="furniture_type[]" placeholder="E.g. sofa, dining seats, bed" required cols="25">
                                </div>

                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="quantity">Quantity *</label> 
                                    <input class="quote-form__input quote-form__input--number required" type="number" id="quantity" name="quantity[]" value="1" min="1" max="50" required>
                                </div>
        
                                <div class="quote-form__input-container">
                                    <label class="quote-form__label" for="description">Furniture Description *</label> 
                                    <textarea class="quote-form__input quote-form__input--textarea required" id="description" name="description[]" placeholder="Please describe the damage to the furniture in detail." required></textarea>
                                </div>
        
                                <div class="quote-form__input-container quote-form__input-container--file">
                                    <label class="quote-form__label" for="ref_img">Reference Image</label> 
                                    <input class="quote-form__input quote-form__input--file" type="file" id="ref_img" name="ref_img[]" accept="images/*">
                                </div>
                            </div>
                        </div>
                        <div class="quote-form__add-item" id="add-item-repair" onclick="addRepairItem()">ADD ANOTHER TYPE</div>
                    </div>
                    <!-- Legal Agreements Section -->
                    <div class="quote-form__section" id="legal-agreements">
                        <h2 class="quote-form__header">Legal Agreements</h2>
                        <p class="quote-form__description">Please read our terms and conditions & data privacy notice.</p>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="legal-terms" name="legal-terms" required>
                            <label class="quote-form__label" for="legal-terms">I have read and agree to the <a href="/legal-agreements.php#terms-and-conditions" target="_blank">Terms and Conditions</a></label>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="legal-data" name="legal-data" required>
                            <label class="quote-form__label" for="legal-data">I have read and agree to the <a href="/legal-agreements.php#data-privacy" target="_blank">Data Privacy Notice</a></label>
                        </div>

                        <!-- Submit Button -->
                        <div class="quote-form__heading-actions">
                            <button class="quote-form__submit" type="submit">Submit Request</button>
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

    function uploadReferenceImage($name, $temp_name) : string {
        $target_dir = "uploadedImages/referenceImages/";
        $target_file = $target_dir . basename($name);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            // File already exists
            return $target_file;
        }

        // Move uploaded file to target directory
        if (move_uploaded_file($temp_name, $target_file)) {
            // File uploaded successfully
            return $target_file;
        } else {
            // Error uploading file
            return '';
        }
    }

    function insertCustom($dimensions, $materials, $fabric, $color) : int {
        global $conn;
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
            )";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':dimensions', $dimensions);
        $stmt->bindParam(':materials', $materials);
        $stmt->bindParam(':fabric', $fabric);
        $stmt->bindParam(':color', $color);
        $stmt->execute();
        return $conn->lastInsertId();
    }

    // If form is completed
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Call the sanitizeInputs function on $_POST
        $_POST = sanitizeInputs($_POST);
        // Extract sanitized $_POST variables into separate variables
        // var_dump($_POST);
        
        $furniture_types = $_POST['furniture_type'];
        $quantities = $_POST['quantity'];
        $descriptions = $_POST['description'];
        $ref_imgs = $_FILES['ref_img'];

        // IF MTO
        if($_POST['service_type'] === 'mto') {
            // IF SINGLE ORDER

            if(count($furniture_types) === 1) {
                $furniture_type = $furniture_types[0];
                $quantity = $quantities[0];
                $description = $descriptions[0];
                $ref_img = $ref_imgs['name'][0] ?? '';
                if (!empty($ref_img) && $ref_imgs['error'][0] == 0) {
                    $ref_img = uploadReferenceImage($ref_img, $ref_imgs['tmp_name'][0]);
                }

                $dimensions = $_POST['dimensions'][0];
                $materials = $_POST['materials'][0];
                $fabric = $_POST['fabric'][0];
                $color = $_POST['color'][0];
                $custom_id = null;
                if(!empty($dimensions) || !empty($materials) || !empty($fabric) || !empty($color)) {
                    $custom_id = insertCustom($dimensions, $materials, $fabric, $color);
                }

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
                    )";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':customer_id', $_SESSION['user_id']);
                $stmt->bindParam(':furniture_type', $furniture_type);
                $stmt->bindParam(':service_type', $_POST['service_type']);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':ref_img_path', $ref_img);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':custom_id', $custom_id);
                $stmt->execute();
            } else {
                // IF MULTIPLE ORDERS
                $dimensions = $_POST['dimensions'];
                $materials = $_POST['materials'];
                $fabric = $_POST['fabric'];
                $colors = $_POST['color'];

                $sql = "
                    INSERT INTO
                        quotes (
                            customer_id,
                            service_type,
                            furniture_type
                        )
                    VALUES (
                        :customer_id,
                        :service_type,
                        :furniture_type
                    )
                ";

                $stmt = $conn->prepare($sql);
                $mult = "multiple";
                $stmt->bindParam(':customer_id', $_SESSION['user_id']);
                $stmt->bindParam(':service_type', $_POST['service_type']);
                $stmt->bindParam(':furniture_type', $mult);
                $stmt->execute();
                $quote_id = $conn->lastInsertId();

                for($i = 0; $i < count($furniture_types); $i++) {
                    $furniture_type = $furniture_types[$i];
                    $quantity = $quantities[$i];
                    $description = $descriptions[$i];
                    $ref_img = $ref_imgs['name'][$i];

                    if (!empty($ref_img) && $ref_imgs['error'][$i] == 0) {
                        $ref_img = uploadReferenceImage($ref_img, $ref_imgs['tmp_name'][$i]);
                    }

                    $dimensions = $_POST['dimensions'][$i];
                    $materials = $_POST['materials'][$i];
                    $fabric = $_POST['fabric'][$i];
                    $color = $_POST['color'][$i];
                    $custom_id = null;

                    if (!empty($dimensions[$i]) || !empty($materials[$i]) || !empty($fabric[$i]) || !empty($colors[$i])) {
                        // Code to execute if any of the variables is not empty
                        $dimension = !empty($dimensions[$i]) ? $dimensions[$i] : '';
                        $material = !empty($materials[$i]) ? $materials[$i] : '';
                        $fab = !empty($fabric[$i]) ? $fabric[$i] : '';
                        $color = !empty($colors[$i]) ? $colors[$i] : '';

                        $custom_id = insertCustom($dimensions, $materials, $fabric, $color);
                    }

                    $query = "
                        INSERT INTO
                            `multis` (
                                `quote_id`,
                                `furniture_type`,
                                `description`,
                                `ref_img_path`,
                                `quantity`,
                                `custom_id`
                                )
                            VALUES (
                                :quote_id,
                                :furniture_type,
                                :description,
                                :ref_img_path,
                                :quantity,
                                :custom_id
                            )";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':quote_id', $quote_id);
                    $stmt->bindParam(':furniture_type', $furniture_type);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':ref_img_path', $ref_img);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->bindParam(':custom_id', $custom_id);
                    $stmt->execute();
                }
            }
        } else if($_POST['service_type'] === "repair") {
            // IF SINGLE ORDER
            if(count($furniture_types) === 1) {
                $furniture_type = $furniture_types[0];
                $quantity = $quantities[0];
                $description = $descriptions[0];
                $ref_img = $ref_imgs['name'][0] ?? '';
                if(!empty($ref_img) && $ref_imgs['error'][0] == 0) {
                    $ref_img = uploadReferenceImage($ref_img, $ref_imgs['tmp_name'][0]);
                }

                $query = "
                    INSERT INTO
                        `quotes` (
                            `customer_id`,
                            `furniture_type`,
                            `service_type`,
                            `description`,
                            `ref_img_path`,
                            `quantity`
                        )
                    VALUES (
                        :customer_id,
                        :furniture_type,
                        :service_type,
                        :description,
                        :ref_img_path,
                        :quantity
                    )";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':customer_id', $_SESSION['user_id']);
                $stmt->bindParam(':furniture_type', $furniture_type);
                $stmt->bindParam(':service_type', $_POST['service_type']);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':ref_img_path', $ref_img);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
            } else {
                // IF MULTIPLE ORDERS
                $sql = "
                    INSERT INTO
                        quotes (
                            customer_id,
                            service_type,
                            furniture_type
                        )
                    VALUES (
                        :customer_id,
                        :service_type,
                        :furniture_type
                    )
                ";

                $stmt = $conn->prepare($sql);
                $mult = "multiple";
                $stmt->bindParam(':customer_id', $_SESSION['user_id']);
                $stmt->bindParam(':service_type', $_POST['service_type']);
                $stmt->bindParam(':furniture_type', $mult);
                $stmt->execute();
                $quote_id = $conn->lastInsertId();

                for($i = 0; $i < count($furniture_types); $i++) {
                    $furniture_type = $furniture_types[$i];
                    $quantity = $quantities[$i];
                    $description = $descriptions[$i];
                    $ref_img = $ref_imgs['name'][$i];
                    if (!empty($ref_img) && $ref_imgs['error'][$i] == 0) {
                        $ref_img = uploadReferenceImage($ref_img, $ref_imgs['tmp_name'][$i]);
                    }

                    $query = "
                        INSERT INTO
                            `multis` (
                                `quote_id`,
                                `furniture_type`,
                                `description`,
                                `ref_img_path`,
                                `quantity`
                                )
                            VALUES (
                                :quote_id,
                                :furniture_type,
                                :description,
                                :ref_img_path,
                                :quantity
                            )";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':quote_id', $quote_id);
                    $stmt->bindParam(':furniture_type', $furniture_type);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':ref_img_path', $ref_img);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->execute();
                }
            }
        }

        // extract($_POST);

        // ----- If customization is selected, insert into quotes_custom table ----- //
    //     if ($_POST['enable_customization'] == 'on') {
    //         try {
    //             $query = "
    //                 INSERT INTO
    //                     `quote_customs` (
    //                         `dimensions`,
    //                         `materials`,
    //                         `fabric`,
    //                         `color`
    //                     )
    //                 VALUES (
    //                     :dimensions,
    //                     :materials,
    //                     :fabric,
    //                     :color
    //                 )
    //             ";
    //             $stmt = $conn->prepare($query);
    //             $stmt->bindParam(':dimensions', $dimensions);
    //             $stmt->bindParam(':materials', $materials);
    //             $stmt->bindParam(':fabric', $fabric);
    //             $stmt->bindParam(':color', $color);
    //             $stmt->execute();
        
    //             $custom_id = $conn->lastInsertId();
    //         } catch (PDOException $e) {
    //             echo "<script>alert(" . $e->getMessage() . ")</script>";
    //             sendAlert("warning", $e->getMessage());
    //         }
    //     }

    //     // ----- Insert into quotes table and create notif ----- //
    //     try {
    //         $query = "
    //             INSERT INTO
    //                 `quotes` (
    //                     `customer_id`,
    //                     `furniture_type`,
    //                     `service_type`,
    //                     `description`,
    //                     `ref_img_path`,
    //                     `quantity`,
    //                     `custom_id`
    //                 )
    //             VALUES (
    //                 :customer_id,
    //                 :furniture_type,
    //                 :service_type,
    //                 :description,
    //                 :ref_img_path,
    //                 :quantity,
    //                 :custom_id
    //             )
    //         ";
    //         $stmt = $conn->prepare($query);
    //         $stmt->bindParam(':customer_id', $_SESSION['user_id']);
    //         $stmt->bindParam(':furniture_type', $furniture_type);
    //         $stmt->bindParam(':service_type', $service_type);
    //         $stmt->bindParam(':description', $description);
    //         $stmt->bindParam(':ref_img_path', $ref_img_path);
    //         $stmt->bindParam(':quantity', $quantity);
    //         $stmt->bindParam(':custom_id', $custom_id);
    //         $stmt->execute();
    //     } catch (PDOException $e) {
    //         echo "<script>alert(" . $e->getMessage() . ")</script>";
    //         sendAlert("warning", $e->getMessage());
    //     }

    //     // ----- Rename and save uploaded image ----- //
    //     // Get the quote ID after insertion
    //     $quote_id = $conn->lastInsertId();

    //     // Get the customer's name
    //     $customer_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';

        

    //     // Check if a file was uploaded
    //     $ref_img_path = null;
    //     $uploadOk = 1;
    //     if (isset($_FILES["ref_img"]) && $_FILES["ref_img"]["error"] == 0) {
    //         $target_dir = "uploadedImages/referenceImages/";
    //         $target_file = $target_dir . basename($_FILES["ref_img"]["name"]);
    //         $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //         // Check if the file already exists
    //         if (file_exists($target_file)) {
    //             echo "Sorry, file already exists.";
    //             $uploadOk = 0;
    //         }

    //         // Check file size
    //         if ($_FILES["ref_img"]["size"] > 5000000) { // Adjust this size limit as needed
    //             echo "Sorry, your file is too large.";
    //             $uploadOk = 0;
    //         }

    //         // Allow only certain file formats
    //         $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    //         if (!in_array($imageFileType, $allowed_extensions)) {
    //             echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
    //             $uploadOk = 0;
    //         }

    //         // Check if $uploadOk is set to 0 by an error
    //         if ($uploadOk) {
    //             if (move_uploaded_file($_FILES["ref_img"]["tmp_name"], $target_file)) {
    //                 $ref_img_path = $target_file;
    //                 echo "The file ". basename( $_FILES["ref_img"]["name"]). " has been uploaded.";
    //             } else {
    //                 echo "Sorry, there was an error uploading your file.";
    //                 $uploadOk = 0;
    //             }
    //         }
    //     } else {
    //         echo "File upload is required.";
    //         $uploadOk = 0;
    //     }



        try {
            // Create a new notification message
            $notif_msg = "You have successfully placed a quote request. Please await confirmation of order."; // Customize the message as needed
            // Call the createNotif function
            if (createNotif($_SESSION['user_id'], $notif_msg, "/my/user_orders.php")) {
                // Notification created successfully
                // echo "Notification created successfully";
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