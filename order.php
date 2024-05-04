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
            <form class="quotation-form" method="post">
                <div class="quotation-form__input-container">
                    <label for="orderType" class="quotation-form__label">Order Type:</label>
                    <select id="orderType" name="orderType" class="quotation-form__select" onchange="toggleInputs()">
                        <option value="repair" class="quotation-form__option">Repair</option>
                        <option value="mto" class="quotation-form__option">Made-to-Order (MTO)</option>
                    </select>
                </div>

                <fieldset class="quotation-form__fieldset quotation-form__fieldset--repair">
                    <legend class="quotation-form__legend">Repair Details</legend>
                    <table class="quotation-form__table">
                        <tr>
                            <td><label class="quotation-form__label">Furniture Pickup Method:</label></td>
                            <td>
                                <input type="radio" id="thirdPartyPickup" name="pickupMethod" value="third_party" class="quotation-form__radio">
                                <label for="thirdPartyPickup" class="quotation-form__radio-label">Third Party</label><br>
                                <input type="radio" id="selfPickup" name="pickupMethod" value="self" class="quotation-form__radio">
                                <label for="selfPickup" class="quotation-form__radio-label">Self</label>
                            </td>
                        </tr>
                        <tr class="quotation-form__pickup-address">
                            <td><label for="pickupAddress" class="quotation-form__label">Pickup Address:</label></td>
                            <td>
                                <textarea id="pickupAddress" name="pickupAddress" class="quotation-form__textarea" rows="4" cols="50"></textarea><br>
                                <input type="checkbox" id="setPickupAddress" name="setPickupAddress" class="quotation-form__checkbox">
                                <label for="setPickupAddress" class="quotation-form__checkbox-label">Set Pickup Address As My Current Address</label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="repairPicture" class="quotation-form__label">Furniture Reference Image:</label></td>
                            <td><input type="file" id="repairPicture" name="repairPicture" accept=".jpg" class="quotation-form__file"></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="quotation-form__fieldset quotation-form__fieldset--mto" style="display:none;">
                    <legend class="quotation-form__legend">Made-to-Order (MTO) Details</legend>
                    <table class="quotation-form__table">
                        <tr>
                            <td><label for="furnitureDimensions" class="quotation-form__label">Furniture Dimensions (in inches):</label></td>
                            <td>
                                <input type="number" id="furnitureWidth" name="furnitureWidth" class="quotation-form__input quotation-form__input--small" placeholder="Width"><br>
                                <input type="number" id="furnitureHeight" name="furnitureHeight" class="quotation-form__input quotation-form__input--small" placeholder="Height"><br>
                                <input type="number" id="furnitureDepth" name="furnitureDepth" class="quotation-form__input quotation-form__input--small" placeholder="Depth">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="furnitureMaterial" class="quotation-form__label">Furniture Material:</label></td>
                            <td><input type="text" id="furnitureMaterial" name="furnitureMaterial" class="quotation-form__input"></td>
                        </tr>
                        <tr>
                            <td><label for="furnitureFabric" class="quotation-form__label">Furniture Fabric:</label></td>
                            <td><input type="text" id="furnitureFabric" name="furnitureFabric" class="quotation-form__input"></td>
                        </tr>
                    </table>
                </fieldset>

                <div class="quotation-form__input-container">
                    <label class="quotation-form__label">Delivery Method:</label>
                    <input type="radio" id="thirdPartyDelivery" name="deliveryMethod" value="third_party" class="quotation-form__radio">
                    <label for="thirdPartyDelivery" class="quotation-form__radio-label">Third Party</label>
                    <input type="radio" id="selfDelivery" name="deliveryMethod" value="self" class="quotation-form__radio">
                    <label for="selfDelivery" class="quotation-form__radio-label">Self</label>
                </div>

                <fieldset class="quotation-form__fieldset quotation-form__fieldset--delivery-address" style="display:none;">
                    <legend class="quotation-form__legend">Delivery Address</legend>
                    <table class="quotation-form__table">
                        <tr>
                            <td><label for="deliveryAddress" class="quotation-form__label">Delivery Address:</label></td>
                            <td>
                                <textarea id="deliveryAddress" name="deliveryAddress" class="quotation-form__textarea" rows="4" cols="50"></textarea><br>
                                <input type="checkbox" id="setDeliveryAddress" name="setDeliveryAddress" class="quotation-form__checkbox">
                                <label for="setDeliveryAddress" class="quotation-form__checkbox-label">Set Delivery Address As My Current Address</label>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <div class="quotation-form__input-container">
                    <label for="furnitureNotes" class="quotation-form__label">Furniture Notes:</label>
                    <textarea id="furnitureNotes" name="furnitureNotes" class="quotation-form__textarea" rows="4" cols="50"></textarea>
                </div>

                <input type="submit" value="Submit" class="quotation-form__submit-button">
            </form>
            <script>
                function toggleInputs() {
                    var orderType = document.getElementById("orderType").value;
                    var repairFieldset = document.querySelector(".quotation-form__fieldset--repair");
                    var mtoFieldset = document.querySelector(".quotation-form__fieldset--mto");

                    if (orderType === "repair") {
                        repairFieldset.style.display = "block";
                        mtoFieldset.style.display = "none";
                        clearMTOFields();
                    } else if (orderType === "mto") {
                        repairFieldset.style.display = "none";
                        mtoFieldset.style.display = "block";
                        clearRepairFields();
                    }
                }

                function clearRepairFields() {
                    document.getElementById("thirdPartyPickup").checked = false;
                    document.getElementById("selfPickup").checked = false;
                    document.getElementById("pickupAddress").value = "";
                    document.getElementById("setPickupAddress").checked = false;
                    document.getElementById("repairPicture").value = "";
                }

                function clearMTOFields() {
                    document.getElementById("furnitureWidth").value = "";
                    document.getElementById("furnitureHeight").value = "";
                    document.getElementById("furnitureDepth").value = "";
                    document.getElementById("furnitureMaterial").value = "";
                    document.getElementById("furnitureFabric").value = "";
                }

                // Trigger toggleInputs() initially to ensure correct display
                toggleInputs();
            </script>
        </div>
        <style>
            /* Order Form Styles */
            .order-form {
            width: 49%;
            height: fit-content;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            }

            .quotation-form__title {
            text-align: center;
            }

            .quotation-form__input-container {
            margin-bottom: 15px;
            }

            .quotation-form__label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            }

            .quotation-form__select,
            .quotation-form__input,
            .quotation-form__textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 3px;
            margin-bottom: 10px;
            }

            .quotation-form__textarea {
            resize: vertical;
            }

            .quotation-form__checkbox-label {
            font-weight: normal;
            }

            .quotation-form__fieldset {
            margin-bottom: 20px;
            }

            .quotation-form__table {
            width: 100%;
            }

            .quotation-form__radio-label {
            margin-right: 10px;
            }

            .quotation-form__submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            }

            .quotation-form__submit-button:hover {
            background-color: #45a049;
            }
        </style>
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
</body>

</html>

<?php
    // session_start();
    include_once 'database_connection.php';
    // var_dump($_SESSION);
    // Function to validate form inputs
    function validateInputs($formData)
    {
        // Check if all required fields are present
        $requiredFields = ['orderType', 'deliveryMethod', 'furnitureNotes'];
        foreach ($requiredFields as $field) {
            if (empty($formData[$field])) {
                return "Please fill in all required fields.";
            }
        }

        // Validate repair image
        if ($formData['orderType'] === 'repair') {
            if (!isset($_FILES['repairPicture']) || $_FILES['repairPicture']['error'] !== UPLOAD_ERR_OK) {
                return "Please upload a valid repair image.";
            }
        }

        // All inputs are valid
        return "";
    }

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate form inputs
        $validationMessage = validateInputs($_POST);
        if ($validationMessage === "") {
            // All inputs are valid, proceed with insertion

            // Extract form data
            extract($_POST);

            // Insert order details into the orders table
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_type, del_method, del_address, note) VALUES (:user_id, :order_type, :del_method, :del_address, :note)");
            $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'order_type' => $orderType,
                'del_method' => $deliveryMethod,
                'del_address' => $deliveryAddress,
                'note' => $furnitureNotes
            ]);

            // Get the last inserted order ID
            $orderId = $conn->lastInsertId();

            // Insert repair image path into the repair table if order type is repair
            if ($orderType === 'repair') {
                $repairImagePath = '/repairImages/' . $_FILES['repairPicture']['name'];
                move_uploaded_file($_FILES['repairPicture']['tmp_name'], __DIR__ . $repairImagePath);
                $stmt = $conn->prepare("INSERT INTO repair (order_id, pickup_method, pickup_address, repair_img_path) VALUES (:order_id, :pickup_method, :pickup_address, :repair_img_path)");
                $stmt->execute([
                    'order_id' => $orderId,
                    'pickup_method' => $thirdPartyPickup ?? $selfPickup,
                    'pickup_address' => $pickupAddress,
                    'repair_img_path' => $repairImagePath
                ]);
            }

            // Output success message to the console
            echo "<script>console.log('Order submitted successfully!');</script>";
        } else {
            // Output validation error message to the console
            echo "<script>console.error('$validationMessage');</script>";
        }
    }
?>