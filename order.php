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
        $autofill_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $autofill_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
        $autofill_contact_number = isset($_SESSION['contact_number']) ? $_SESSION['contact_number'] : '';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/order.css">
</head>

<body>
    <?php 
        include_once('database_connection.php');

        // Get all addresses of the user
        $query = "
            SELECT 
                * 
            FROM 
                `addresses`
            WHERE 
                `user_id` = :user_id
        ";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $addresses = $stmt->fetchAll(); 
    ?>
    <?php 
        require_once('header.php');
        $needs_cta = false;
        require_once('featured.php');
    ?>
    <div class="order-page__content">
        <div class="order-form">
            <h1 class="quotation-form__title">Quotation Form</h1>
            <p>Request a quote to get custom pricing. Please take a moment to fill in the form.</p>
            <form class="quotation-form" method="post" enctype="multipart/form-data">
                <fieldset class="quotation-form__fieldset" id="personal_info">
                    <legend class="quotation-form__legend">Personal Information </legend>
                    <p class="quotation-form__description">
                        Please review the following personal information and ensure all details are correct.
                        If you wish to change details, <a href="my/account.php">click here</a>.
                    </p>
                    <div class="quotation-form__input-container-group">
                        <!-- Customer Name [ TEXT ] -->
                        <div class="quotation-form__input-container">
                            <label for="customer_name" class="quotation-form__label">Full Name:</label>
                            <input type="text" id="customer_name" name="customer_name" class="quotation-form__input quotation-form__input--readonly" placeholder="Enter your full name" value="<?php echo htmlspecialchars($autofill_name); ?>" required readonly>
                        </div>
                        
                        <!-- Contact Information [ PHONE, EMAIL ] -->
                        <div class="quotation-form__input-container">
                            <label for="contact_phone" class="quotation-form__label">Phone Number:</label>
                            <input type="tel" id="contact_phone" name="contact_phone" class="quotation-form__input quotation-form__input--readonly" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($autofill_contact_number); ?>" required readonly>
                        </div>

                        <div class="quotation-form__input-container">
                            <label for="contact_email" class="quotation-form__label">Email Address:</label>
                            <input type="email" id="contact_email" name="contact_email" class="quotation-form__input quotation-form__input--readonly" placeholder="Enter your email address" value="<?php echo htmlspecialchars($autofill_email); ?>" required readonly>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="quotation-form__fieldset" id="order_details">
                    <legend class="quotation-form__legend">Order Details</legend>
                    <p class="quotation-form__description">
                        Please provide details and instructions about your order.
                    </p>
                    <div class="quotation-form__input-container-group">
                        <!-- Order Type [ ENUM(repair, mto) ] -->
                        <div class="quotation-form__input-container">
                            <label for="order_type" class="quotation-form__label">What type of order do you wish to place?</label>
                            <select id="order_type" name="order_type" class="quotation-form__select" onchange="toggleInputs()" required>
                                <option value="repair" class="quotation-form__option">Repair</option>
                                <option value="mto" class="quotation-form__option">Made-to-Order (MTO)</option>
                            </select>
                        </div>
                        <!-- Furniture Type [ TEXT ] -->
                        <div class="quotation-form__input-container">
                            <label for="furniture_type" class="quotation-form__label">What furniture are we working on?</label>
                            <input type="text" id="furniture_type" name="furniture_type" class="quotation-form__input" placeholder="E.g. sofa, bed, chair" required>
                        </div>
                        <!-- notes [ TEXT ] -->
                        <div class="quotation-form__input-container">
                            <label for="notes" class="quotation-form__label">Furniture Notes:</label>
                            <textarea id="notes" name="notes" class="quotation-form__textarea" rows="4" cols="50" placeholder="Provide a description of your order" required></textarea>
                        </div>
                        <!-- referenceImage [ FILE ] -->
                        <div class="quotation-form__input-container">
                            <label for="referenceImage" class="quotation-form__label">Please provide a reference image of the furniture:</label>
                            <input type="file" id="referenceImage" name="referenceImage" accept=".jpg" class="quotation-form__file">
                        </div>
                    </div>
                </fieldset>
                <fieldset class="quotation-form__fieldset" id="delivery_details">
                    <legend class="quotation-form__legend">Delivery Details</legend>
                    <p class="quotation-form__description">
                        Please provide details about the transportation of your furniture.
                    </p>
                    <div class="quotation-form__input-container-group--repair">
                        <!-- pickup_method [ ENUM(third_party, self) ] -->
                        <div class="quotation-form__input-container">
                            <label for="pickup_method" class="quotation-form__label">How shall we pick up the furniture to be repaired?</label>
                            <select id="pickup_method" name="pickup_method" class="quotation-form__select">
                                <option value="third_party" class="quotation-form__option">Third-party delivery service</option>
                                <option value="self" class="quotation-form__option">I will drop it off at the business location</option>
                            </select>
                        </div>
                        <!-- pickup_address [ TEXT ] -->
                        <div class="quotation-form__input-container">
                            <label for="pickup_address" class="quotation-form__label">Where shall we pick up the furniture to be repaired?</label>
                            <input list="address-options" id="pickup_address" name="pickup_address" class="quotation-form__textarea" placeholder="Enter the pickup address here.">
                            <datalist id="address-options">
                                <?php foreach ($addresses as $address) { ?>
                                    <option value="<?php echo $address["address"] ?>"><?= htmlspecialchars($address["address"]) ?></option>
                                <?php } ?>
                            </datalist>
                            <input type="hidden" name="pickup_address_id">
                        </div>
                    </div>
                    <div class="quotation-form__input-container-group">
                        <!-- del_method [ ENUM(third_party, self) ] -->
                        <div class="quotation-form__input-container">
                            <label for="del_method" class="quotation-form__label">How shall we deliver the furniture?</label>
                            <select id="del_method" name="del_method" class="quotation-form__select" required>
                                <option value="third_party" class="quotation-form__option">Third-party delivery service</option>
                                <option value="self" class="quotation-form__option">I will pick it up at the business location</option>
                            </select>
                        </div>
                        
                        <!-- del_address [ TEXT ] -->
                        <div class="quotation-form__input-container">
                            <label for="del_address" class="quotation-form__label">Where shall we deliver the furniture to be repaired?</label>
                            <input list="address-options" id="del_address" name="del_address" class="quotation-form__textarea" rows="4" cols="50" placeholder="Enter the delivery address here." required><br>
                        </div>
                    </div>
                </fieldset>
                <div class="quotation-form__navigation">
                    <button type="button" class="quotation-form__prev-button" onclick="prevFieldset()">Previous</button>
                    <button type="button" class="quotation-form__next-button" onclick="nextFieldset()">Next</button>
                    <input type="submit" value="Submit Quotation Form" class="quotation-form__submit-button">
                </div>
            </form>
        </div>
        <script src="js/order.js"></script>
        <div class="faq">
            <h1 class="faq__title">Frequently Asked Questions</h1>
            <ol class="faq__list">
                <?php
                    $sql = "SELECT * FROM faqs";
                    $stmt = $conn->query($sql);
                    $faqs = $stmt->fetchAll();
                    foreach ($faqs as $faq) {
                        echo "
                            <div class='faq__item'>
                                <li class='faq__question'>{$faq['question']}</li>
                                <p class='faq__answer'>{$faq['answer']}</p>
                            </div>
                        ";
                    }
                ?>
            </ol>
        </div>
    </div>
    <div class="order-testimonials-cta">
        <p>See What Our Clients Say</p>
        <a href="testimonials.php">See More</a>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
</body>

</html>

<?php
    // Include database connection
    include_once("alert.php");
    include_once("api/CheckAddress.php");

    function sanitize_input($data) {
        return htmlspecialchars(strip_tags($data));
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try { // Preparing values to be inserted
            // Sanitize inputs
            $order_type = sanitize_input($_POST['order_type']);
            $furniture_type = sanitize_input($_POST['furniture_type']);
            $notes = sanitize_input($_POST['notes']);
            $del_method = sanitize_input($_POST['del_method']);
            $del_address_id = getAddressId(trim($_POST['del_address']), $conn, $_SESSION['user_id']);
        
            // Handle file upload
            $ref_img_path = null;
            $uploadOk = 1;
            if (isset($_FILES["referenceImage"]) && $_FILES["referenceImage"]["error"] == 0) {
                $target_dir = "uploadedImages/referenceImages/";
                $target_file = $target_dir . basename($_FILES["referenceImage"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if image file is a JPG
                if ($imageFileType != "jpg") {
                    $uploadOk = 0;
                    echo "Sorry, only JPG files are allowed.";
                    sendAlert("error", "Sorry, only JPG files are allowed.");
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk && move_uploaded_file($_FILES["referenceImage"]["tmp_name"], $target_file)) {
                    $ref_img_path = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    sendAlert("error", "Sorry, there was an error uploading your file.");
                    $uploadOk = 0;
                }
            } else {
                $uploadOk = 0;
                echo "File upload is required.";
                sendAlert("warning", "File upload is required.");
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            sendAlert("error", "Error: " . $e->getMessage());
        }
    
        try { // Inserting the values
            // Insert into orders table
            $query = "
                INSERT INTO 
                    orders (
                        user_id, 
                        furniture_type, 
                        order_type, 
                        ref_img_path, 
                        del_method, 
                        del_address_id, 
                        notes
                    ) 
                VALUES (
                    :user_id, 
                    :furniture_type, 
                    :order_type, 
                    :ref_img_path, 
                    :del_method, 
                    :del_address_id, 
                    :notes
                )
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION["user_id"]);
            $stmt->bindParam(':furniture_type', $furniture_type);
            $stmt->bindParam(':order_type', $order_type);
            $stmt->bindParam(':ref_img_path', $ref_img_path);
            $stmt->bindParam(':del_method', $del_method);
            $stmt->bindParam(':del_address_id', $del_address_id);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();
    
            $order_id = $conn->lastInsertId();
    
            // Insert into pickup table if order_type is "repair"
            if ($order_type === 'repair') {
                $pickup_method = isset($_POST['pickup_method']) ? sanitize_input($_POST['pickup_method']) : null;
                $pickup_address_id = isset($_POST['pickup_address']) ? getAddressId(sanitize_input(trim($_POST['pickup_address'])), $conn, $_SESSION['user_id']) : null;

                $query = "
                    INSERT INTO 
                        pickup (
                            order_id, 
                            pickup_method, 
                            pickup_address_id
                        ) 
                    VALUES (
                        :order_id, 
                        :pickup_method, 
                        :pickup_address_id
                    )
                ";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':pickup_method', $pickup_method);
                $stmt->bindParam(':pickup_address_id', $pickup_address_id);
                $stmt->execute();
            }
    
            echo "Order placed successfully.";
            sendAlert("success", "Order placed successfully.");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            sendAlert("error", "Error: " . $e->getMessage());
        }

        try {
            // Create a new notification message
            $notif_msg = "You have successfully placed a quote request. Please await confirmation of order."; // Customize the message as needed

            // Call the createNotif function
            if (createNotif($_SESSION['user_id'], $notif_msg, "/my/user_orders.php")) {
                // Notification created successfully
                echo "Notification created successfully";
            } else {
                // Failed to create notification
                echo "Failed to create notification";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            sendAlert("error", "Error: " . $e->getMessage());
        }
    }
?>