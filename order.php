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
    <?php include_once("header.php") ?>
    <img src="websiteimages/orderfeaturedimg.png" class="order-featured-img">
    <h2 class="order-page-title">Design, Craft, Quote - All in One Place</h2>
    <div class="order-container">
        <div class="order-form">
            <h2 class="quotation-form__title">Quotation Form</h2>
            <form class="quotation-form" method="post" enctype="multipart/form-data">
                <!-- Order Type [ ENUM(repair, mto) ] -->
                <div class="quotation-form__input-container">
                    <label for="order_type" class="quotation-form__label">What type of order do you wish to place?</label>
                    <select id="order_type" name="order_type" class="quotation-form__select" onchange="toggleInputs()">
                        <option value="repair" class="quotation-form__option">Repair</option>
                        <option value="mto" class="quotation-form__option">Made-to-Order (MTO)</option>
                    </select>
                </div>

                <!-- Furniture Type [ TEXT ] -->
                <div class="quotation-form__input-container">
                    <label for="furniture_type" class="quotation-form__label">What furniture are we working on?</label>
                    <input type="text" id="furniture_type" name="furniture_type" class="quotation-form__input" placeholder="E.g. sofa, bed, chair" required>
                </div>

                <!-- IF REPAIR -->
                <fieldset class="quotation-form__fieldset quotation-form__fieldset--repair">
                    <legend class="quotation-form__legend">Furniture Details: </legend>

                    <!-- pickup_method [ ENUM(third_party, self) ] -->
                    <div class="quotation-form__input-container">
                        <label for="pickup_method" class="quotation-form__label">How shall we pick up the furniture to be repaired?</label>
                        <select id="pickup_method" name="pickup_method" class="quotation-form__select"">
                            <option value="third_party" class="quotation-form__option">Through a third-party delivery service</option>
                            <option value="self" class="quotation-form__option">I will drop it off at the business location</option>
                        </select>
                    </div>

                    <!-- pickup_address [ TEXT ] -->
                    <div class="quotation-form__input-container">
                        <label for="pickup_address" class="quotation-form__label">Where shall we pick up the furniture to be repaired?</label>
                        <textarea id="pickup_address" name="pickup_address" class="quotation-form__textarea" rows="4" cols="50" placeholder="Enter the pickup address here. If you have selected 'self-pickup method' option, enter 'N/A' instead." required></textarea><br>
                        <input type="checkbox" id="setPickupAddress" name="setPickupAddress" class="quotation-form__checkbox">
                        <label for="setPickupAddress" class="quotation-form__checkbox-label">Set as my current address</label>
                    </div>

                    <!-- repairPicture [ FILE ] -->
                    <div class="quotation-form__input-container">
                        <label for="repairPicture" class="quotation-form__label">Please provide a reference image of the furniture:</label>
                        <input type="file" id="repairPicture" name="repairPicture" accept=".jpg" class="quotation-form__file">
                    </div>
                </fieldset>

                <!-- IF MTO -->
                <fieldset class="quotation-form__fieldset quotation-form__fieldset--mto">
                    <legend class="quotation-form__legend">Furniture Details: </legend>

                    <!-- height, width, depth [ NUMBER ] -->
                    <div class="quotation-form__input-container">
                        <label class="quotation-form__label">Furniture Dimensions (in inches):</label>
                        <input type="number" id="width" name="width" class="quotation-form__input quotation-form__input--small" placeholder="Width" required><br>
                        <input type="number" id="height" name="height" class="quotation-form__input quotation-form__input--small" placeholder="Height" required><br>
                        <input type="number" id="depth" name="depth" class="quotation-form__input quotation-form__input--small" placeholder="Depth" required>
                    </div>

                    <!-- material [ TEXT ] -->
                    <div class="quotation-form__input-container">
                        <label for="material" class="quotation-form__label">Furniture Material:</label>
                        <input type="text" id="material" name="material" class="quotation-form__input" placeholder="E.g. wood, plastic, metal" required>
                    </div>
                </fieldset>

                <!-- del_method [ ENUM(third_party, self) ] -->
                <div class="quotation-form__input-container">
                    <label for="del_method" class="quotation-form__label">How shall we deliver the furniture?</label>
                    <select id="del_method" name="del_method" class="quotation-form__select"">
                        <option value="third_party" class="quotation-form__option">Through a third-party delivery service</option>
                        <option value="self" class="quotation-form__option">I will pick it up at the business location</option>
                    </select>
                </div>
                
                <!-- del_address [ TEXT ] -->
                <div class="quotation-form__input-container">
                    <label for="del_address" class="quotation-form__label">Where shall we deliver the furniture to be repaired?</label>
                    <textarea id="del_address" name="del_address" class="quotation-form__textarea" rows="4" cols="50" placeholder="Enter the delivery address here. If you have selected 'self-delivery method' option, enter 'N/A' instead." required></textarea><br>
                    <input type="checkbox" id="setDeliveryAddress" name="setDeliveryAddress" class="quotation-form__checkbox">
                    <label for="setDeliveryAddress" class="quotation-form__checkbox-label">Set as my current address</label>
                </div>

                <!-- notes [ TEXT ] -->
                <div class="quotation-form__input-container">
                    <label for="notes" class="quotation-form__label">Furniture Notes:</label>
                    <textarea id="notes" name="notes" class="quotation-form__textarea" rows="4" cols="50" placeholder="Provide a description of your order"></textarea>
                </div>

                <input type="submit" value="Submit" class="quotation-form__submit-button">
            </form>
        </div>
        <div class="faq-section">
            <h3>Frequently Asked Questions</h3>
            <div class="questions">
                <span class="questions-title">1. How does the ordering process work?</span>
                <p>
                    Our ordering process is simple and convenient. Visit our Order webpage and select . Once we receive your submission, our team will 
                    review it and get in touch with you to discuss the specifics.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">2. What kind of orders can I place?</span>
                <p>
                    We specialize in repair worn-down furniture fabrics as well as crafting unique, made-to-order 
                    furniture pieces. Whether you're looking for a custom-sized sofa, a personalized dining table, 
                    or a unique cleopatra sofa, we can bring your ideas to life. Feel free to share your design 
                    preferences in the order form.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">Can I customize the materials used in my furniture?</span>
                <p>
                    Absolutely! We offer a range of materials to suit your preferences. In the order form, you can
                    specify the type of wood, fabric, or other materials you would like us to use. Our team will work
                    closely with you to ensure your custom furniture meets your expectations.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">What information should I provide in the order form?</span>
                <p>
                    The order form is designed to capture all the necessary details for your custom furniture. Please
                    provide information such as dimensions, preferred materials, color preferences, and any specific
                    design elements you have in mind. The more details you provide, the better we can tailor the
                    furniture to your liking.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">How long does it take to receive my custom furniture?</span>
                <p>
                    The production time for custom furniture varies based on the complexity of the design and the
                    materials chosen. Our team will provide you with a timeline once we review your order. Rest assured,
                    we strive to complete orders in a timely manner without compromising on quality.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">Is there a deposit required for custom orders?</span>
                <p>
                    Yes, a deposit is required to initiate the production of your custom furniture. The exact amount
                    will be communicated to you once we review your order. The remaining balance will be due upon
                    completion, prior to delivery or pickup.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">Can I make changes to my order after submission?</span>
                <p>
                    We understand that preferences may evolve. If you need to make changes to your order, please contact
                    us as soon as possible. We will do our best to accommodate changes, although some
                    modifications may affect the timeline and cost.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">Do you offer delivery services?</span>
                <p>
                    Yes, we offer delivery services for your convenience. The delivery cost will be calculated based on
                    your location. Alternatively, you can arrange to pick up your custom furniture directly from our
                    workshop.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">What is your return policy for custom orders?</span>
                <p>
                    Since each piece is made to order based on your specific requirements, we do not accept returns on
                    custom furniture. However, we are committed to ensuring your satisfaction, and we will address any
                    issues or concerns to the best of our ability.
                </p>
            </div>
            <div class="questions">
                <span class="questions-title">How can I contact customer support for further assistance?</span>
                <p>
                    If you have any questions or need assistance, please reach out to us via the
                    contact information provided on our website. We're here to help you throughout the custom furniture
                    ordering process.
                </p>
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
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit;
    }

    // Include database connection
    require_once 'database_connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Prepare and bind parameters for orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, furniture_type, order_type, del_method, del_address, notes) 
                                VALUES (:user_id, :furniture_type, :order_type, :del_method, :del_address, :notes)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':furniture_type', $_POST['furniture_type']);
        $stmt->bindParam(':order_type', $_POST['order_type']);
        $stmt->bindParam(':del_method', $_POST['del_method']);
        $stmt->bindParam(':del_address', $_POST['del_address']);
        $stmt->bindParam(':notes', $_POST['notes']);

        // Execute orders table insertion
        $stmt->execute();

        // Get the last inserted order_id
        $order_id = $conn->lastInsertId();

        // Insert into repair or mto table based on order_type
        if ($_POST['order_type'] === "repair") {
            // Prepare and bind parameters for repair table
            $stmt = $conn->prepare("INSERT INTO repair (order_id, pickup_method, pickup_address, repair_img_path) 
                                    VALUES (:order_id, :pickup_method, :pickup_address, :repair_img_path)");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':pickup_method', $_POST['pickup_method']);
            $stmt->bindParam(':pickup_address', $_POST['pickup_address']);

            if (isset($_FILES["repairPicture"]) && $_FILES["repairPicture"]["error"] == 0) {
                $target_dir = "uploadedImages/repairImages/";
                $target_file = $target_dir . basename($_FILES["repairPicture"]["name"]);
                
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($_FILES["repairPicture"]["tmp_name"], $target_file)) {
                    // File uploaded successfully, save file path to database
                    $stmt->bindParam(':repair_img_path', $target_file);
                    // echo "The file ". htmlspecialchars(basename($_FILES["repairPicture"]["name"])). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Error uploading file: " . $_FILES["repairPicture"]["error"];
            }
            
            // Upload image and save path

            // Execute repair table insertion
            $stmt->execute();
        } elseif ($_POST['order_type'] === "mto") {
            // Prepare and bind parameters for mto table
            $stmt = $conn->prepare("INSERT INTO mto (order_id, height, width, depth, material) 
                                    VALUES (:order_id, :height, :width, :depth, :material)");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':height', $_POST['height']);
            $stmt->bindParam(':width', $_POST['width']);
            $stmt->bindParam(':depth', $_POST['depth']);
            $stmt->bindParam(':material', $_POST['material']);

            // Execute mto table insertion
            $stmt->execute();
        }

        // Close statement
        $stmt = null;

        // Close connection
        $conn = null;
    }
?>