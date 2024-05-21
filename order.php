<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit;
    } else {
        $autofill_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $autofill_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
        $autofill_customer_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : '';
        $autofill_contact_number = isset($_SESSION['contact_number']) ? $_SESSION['contact_number'] : '';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/order.css">
</head>

<body>
    <?php 
    include_once("header.php");
    ?>
    <div class="featured-banner">
        <h1 class="order-page__title">Design, Craft, Quote - All in One Place</h2>
    </div>
    <div class="order-page__content">
        <div class="order-form">
            <h1 class="quotation-form__title">Quotation Form</h1>
            <p>Request a quote to get custom pricing. Please take a moment to fill in the form.</p>
            <form class="quotation-form" method="post" enctype="multipart/form-data">
                <fieldset class="quotation-form__fieldset" id="personal_info">
                    <legend class="quotation-form__legend">Personal Information: </legend>
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
                            <input type="file" id="referenceImage" name="referenceImage" accept=".jpg" class="quotation-form__file" required>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="quotation-form__fieldset" id="delivery_details">
                    <legend class="quotation-form__legend">Delivery Details</legend>
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
                            <textarea id="pickup_address" name="pickup_address" class="quotation-form__textarea" rows="4" cols="50" placeholder="Enter the pickup address here."></textarea><br>
                            <input type="checkbox" id="setPickupAddress" name="setPickupAddress" class="quotation-form__checkbox">
                            <label for="setPickupAddress" class="quotation-form__checkbox-label">Set as my current address</label>
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
                            <textarea id="del_address" name="del_address" class="quotation-form__textarea" rows="4" cols="50" placeholder="Enter the delivery address here." required></textarea><br>
                            <input type="checkbox" id="setDeliveryAddress" name="setDeliveryAddress" class="quotation-form__checkbox">
                            <label for="setDeliveryAddress" class="quotation-form__checkbox-label">Set as my current address</label>
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
        <div class="faq">
            <h1 class="faq__title">Frequently Asked Questions</h1>
            <div class="faq__list">
                <div class="faq__item">
                    <span class="faq__question">1. How does the ordering process work?</span>
                    <p class="faq__answer">
                        Our ordering process is simple and convenient. Visit our Order webpage and select . Once we receive your submission, our team will 
                        review it and get in touch with you to discuss the specifics.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">2. What kind of orders can I place?</span>
                    <p class="faq__answer">
                        We specialize in repair worn-down furniture fabrics as well as crafting unique, made-to-order 
                        furniture pieces. Whether you're looking for a custom-sized sofa, a personalized dining table, 
                        or a unique cleopatra sofa, we can bring your ideas to life. Feel free to share your design 
                        preferences in the order form.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">3. Can I customize the materials used in my furniture?</span>
                    <p class="faq__answer">
                        We specialize in repair worn-down furniture fabrics as well as crafting unique, made-to-order 
                        furniture pieces. Whether you're looking for a custom-sized sofa, a personalized dining table, 
                        or a unique cleopatra sofa, we can bring your ideas to life. Feel free to share your design 
                        preferences in the order form.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">4. What information should I provide in the order form?</span>
                    <p class="faq__answer">
                        The order form is designed to capture all the necessary details for your custom furniture. Please
                        provide information such as dimensions, preferred materials, color preferences, and any specific
                        design elements you have in mind. The more details you provide, the better we can tailor the
                        furniture to your liking.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">5. How long does it take to receive my custom furniture?</span>
                    <p class="faq__answer">
                        The production time for custom furniture varies based on the complexity of the design and the
                        materials chosen. Our team will provide you with a timeline once we review your order. Rest assured,
                        we strive to complete orders in a timely manner without compromising on quality.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">6. Is there a deposit required for custom orders?</span>
                    <p class="faq__answer">
                        Yes, a deposit is required to initiate the production of your custom furniture. The exact amount
                        will be communicated to you once we review your order. The remaining balance will be due upon
                        completion, prior to delivery or pickup.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">7. Can I make changes to my order after submission?</span>
                    <p class="faq__answer">
                        We understand that preferences may evolve. If you need to make changes to your order, please contact
                        us as soon as possible. We will do our best to accommodate changes, although some
                        modifications may affect the timeline and cost.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">8. Do you offer delivery services?</span>
                    <p class="faq__answer">
                        Yes, we offer delivery services for your convenience. The delivery cost will be calculated based on
                        your location. Alternatively, you can arrange to pick up your custom furniture directly from our
                        workshop.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">9. What is your return policy for custom orders?</span>
                    <p class="faq__answer">
                        Since each piece is made to order based on your specific requirements, we do not accept returns on
                        custom furniture. However, we are committed to ensuring your satisfaction, and we will address any
                        issues or concerns to the best of our ability.
                    </p>
                </div>
                <div class="faq__item">
                    <span class="faq__question">10. How can I contact customer support for further assistance?</span>
                    <p class="faq__answer">
                        If you have any questions or need assistance, please reach out to us via the
                        contact information provided on our website. We're here to help you throughout the custom furniture
                        ordering process.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="order-testimonials-cta">
        <p>See What Our Clients Say</p>
        <a href="testimonials.php">See More</a>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
    <script src="js/order.js"></script>
</body>

</html>

<?php
    // Include database connection
    include_once('database_connection.php');
    include_once("alert.php");

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
            $del_address = sanitize_input($_POST['del_address']);
            $pickup_method = isset($_POST['pickup_method']) ? sanitize_input($_POST['pickup_method']) : null;
            $pickup_address = isset($_POST['pickup_address']) ? sanitize_input($_POST['pickup_address']) : null;
        
            // Handle file upload
            $ref_img_path = null;
            $uploadOk = 1;
            if (isset($_FILES["referenceImage"]) && $_FILES["referenceImage"]["error"] == 0) {
                $target_dir = "uploadedImages/";
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
            $query = "INSERT INTO orders (user_id, furniture_type, order_type, ref_img_path, del_method, del_address, notes) VALUES (:user_id, :furniture_type, :order_type, :ref_img_path, :del_method, :del_address, :notes)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION["user_id"]);
            $stmt->bindParam(':furniture_type', $furniture_type);
            $stmt->bindParam(':order_type', $order_type);
            $stmt->bindParam(':ref_img_path', $ref_img_path);
            $stmt->bindParam(':del_method', $del_method);
            $stmt->bindParam(':del_address', $del_address);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();
    
            $order_id = $conn->lastInsertId();
    
            // Insert into pickup table if order_type is "repair"
            if ($order_type === 'repair') {
                $query = "INSERT INTO pickup (order_id, pickup_method, pickup_address) VALUES (:order_id, :pickup_method, :pickup_address)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':pickup_method', $pickup_method);
                $stmt->bindParam(':pickup_address', $pickup_address);
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
            $notif_msg = "New quotation form submitted by: " . $_SESSION['name']; // Customize the message as needed

            // Call the createNotif function
            if (createNotif($_SESSION['user_id'], $notif_msg)) {
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