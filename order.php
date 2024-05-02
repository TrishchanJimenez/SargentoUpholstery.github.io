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
                    <label for="orderCategory" class="quotation-form__label">Category:</label>
                    <select id="orderCategory" name="orderCategory" class="quotation-form__select" onchange="toggleInputs()">
                        <option value="repair" class="quotation-form__option">Repair</option>
                        <option value="customized" class="quotation-form__option">Customized</option>
                    </select>
                </div>

                <fieldset class="quotation-form__fieldset quotation-form__fieldset--repair">
                    <legend class="quotation-form__legend">Repair</legend>
                    <table class="quotation-form__table">
                        <tr>
                            <td><label for="orderRepairType" class="quotation-form__label">Type of Furniture to Repair:</label></td>
                            <td><input type="text" id="orderRepairType" name="orderRepairType" class="quotation-form__input"></td>
                        </tr>
                        <tr>
                            <td><label for="orderRepairPicture" class="quotation-form__label">Picture of Furniture:</label></td>
                            <td><input type="file" id="orderRepairPicture" name="orderRepairPicture" class="quotation-form__file"></td>
                        </tr>
                        <tr>
                            <td><label for="orderRepairNote" class="quotation-form__label">Note:</label></td>
                            <td><textarea id="orderRepairNote" name="orderRepairNote" class="quotation-form__textarea" rows="4" cols="50"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="orderRepairDeliveryAddress" class="quotation-form__label">Delivery Address:</label></td>
                            <td><textarea id="orderRepairDeliveryAddress" name="orderRepairDeliveryAddress" class="quotation-form__textarea" rows="4" cols="50"></textarea></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="quotation-form__fieldset quotation-form__fieldset--customized" style="display:none;">
                    <legend class="quotation-form__legend">Customized</legend>
                    <table class="quotation-form__table">
                        <tr>
                            <td><label for="orderCustomType" class="quotation-form__label">Type of Furniture:</label></td>
                            <td><input type="text" id="orderCustomType" name="orderCustomType" class="quotation-form__input"  ></td>
                        </tr>
                        <tr>
                            <td><label class="quotation-form__label">Dimensions (Height, Width, Depth) in inches:</label></td>
                            <td>
                                <input type="number" id="orderCustomHeight" name="orderCustomHeight" class="quotation-form__input quotation-form__input--small" placeholder="Height"  ><br>
                                <input type="number" id="orderCustomWidth" name="orderCustomWidth" class="quotation-form__input quotation-form__input--small" placeholder="Width"  ><br>
                                <input type="number" id="orderCustomDepth" name="orderCustomDepth" class="quotation-form__input quotation-form__input--small" placeholder="Depth"  ><br>
                            </td>
                        </tr>
                        <tr>
                            <td><label class="quotation-form__label">Materials:</label></td>
                            <td>
                                <input type="radio" id="plastic" name="orderCustomMaterial" value="Plastic" class="quotation-form__radio"  >
                                <label for="plastic" class="quotation-form__radio-label">Plastic</label><br>
                                <input type="radio" id="metal" name="orderCustomMaterial" value="Metal" class="quotation-form__radio"  >
                                <label for="metal" class="quotation-form__radio-label">Metal</label><br>
                                <input type="radio" id="wood" name="orderCustomMaterial" value="Wood" class="quotation-form__radio"  >
                                <label for="wood" class="quotation-form__radio-label">Wood</label><br>
                                <input type="radio" id="other_material" name="orderCustomMaterial" value="Other" class="quotation-form__radio"  >
                                <label for="other_material" class="quotation-form__radio-label">Other (Please specify in Note)</label><br>
                            </td>
                        </tr>
                        <tr>
                            <td><label class="quotation-form__label">Fabric:</label></td>
                            <td>
                                <input type="radio" id="cotton" name="orderCustomFabric" value="Cotton" class="quotation-form__radio">
                                <label for="cotton" class="quotation-form__radio-label">Cotton</label><br>
                                <input type="radio" id="polyester" name="orderCustomFabric" value="Polyester" class="quotation-form__radio">
                                <label for="polyester" class="quotation-form__radio-label">Polyester</label><br>
                                <input type="radio" id="linen" name="orderCustomFabric" value="Linen" class="quotation-form__radio">
                                <label for="linen" class="quotation-form__radio-label">Linen</label><br>
                                <input type="radio" id="vinyl" name="orderCustomFabric" value="Vinyl" class="quotation-form__radio">
                                <label for="vinyl" class="quotation-form__radio-label">Vinyl</label><br>
                                <input type="radio" id="other_fabric" name="orderCustomFabric" value="Other" class="quotation-form__radio"  >
                                <label for="other_fabric" class="quotation-form__radio-label">Other (Please specify in Note)</label><br>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="orderCustomNote" class="quotation-form__label">Note:</label></td>
                            <td><textarea id="orderCustomNote" name="orderCustomNote" class="quotation-form__textarea" rows="4" cols="50"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="orderCustomDeliveryAddress" class="quotation-form__label">Delivery Address:</label></td>
                            <td><textarea id="orderCustomDeliveryAddress" name="orderCustomDeliveryAddress" class="quotation-form__textarea" rows="4" cols="50"  ></textarea></td>
                        </tr>
                    </table>
                </fieldset>

                <input type="submit" value="Submit" class="quotation-form__submit-button">
            </form>
            <script src="js/order.js"></script>
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
</body>

</html>

<?php
/*
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the variables from $_POST
        $orderCategory = $_POST["orderCategory"];

        // Initialize other variables
        // Both:
        $orderType = '';
        $orderNote = '';
        $orderDeliveryAddress = '';
        // Customized:
        $orderCustomDimensions = [];
        $orderCustomMaterial = '';
        $orderCustomFabric = '';
        // Repair:
        $orderRepairPicture = '';

        // Process variables based on orderCategory
        switch ($orderCategory) {
            case "repair":
                $orderType = $_POST["orderRepairType"];
                if (isset($_FILES['orderRepairPicture']) && $_FILES['orderRepairPicture']['error'] === UPLOAD_ERR_OK) {
                    $tmpFilePath = $_FILES['orderRepairPicture']['tmp_name'];
                    $orderRepairPicture = file_get_contents($tmpFilePath);
                } else {
                    echo "Error uploading file.";
                }
                $orderNote = $_POST["orderRepairNote"];
                $orderDeliveryAddress = $_POST["orderRepairDeliveryAddress"];
                break;

            case "customized":
                $orderType = $_POST["orderCustomType"];
                $orderCustomDimensions = array(
                    $_POST["orderCustomHeight"],
                    $_POST["orderCustomWidth"],
                    $_POST["orderCustomDepth"]
                );
                $orderCustomMaterial = $_POST["orderCustomMaterial"];
                $orderCustomFabric = $_POST["orderCustomFabric"];
                $orderNote = $_POST["orderCustomNote"];
                $orderDeliveryAddress = $_POST["orderCustomDeliveryAddress"];
                break;

            default:
                // Handle default case if needed
                break;
        }

        // Define the content to write to the text file
        $content = "Order Category: $orderCategory\n";
        $content .= "Order Type: $orderType\n";
        $content .= "Order Dimensions: " . implode(" x ", $orderCustomDimensions) . "\n";
        $content .= "Order Material: $orderCustomMaterial\n";
        $content .= "Order Fabric: $orderCustomFabric\n";
        $content .= "Order Note: $orderNote\n";
        $content .= "Order Delivery Address: $orderDeliveryAddress\n";
        $content .= "Order Repair Picture: " . base64_encode($orderRepairPicture) . "\n";

        // Write the content to a text file
        $file = 'order_details.txt';
        file_put_contents($file, $content);
        if (file_put_contents($file, $content) !== false) {
            // Provide feedback to the user on success
            echo '<script>alert("Order details written to ' . $file . '");</script>';
        } else {
            // Provide feedback to the user on failure
            echo '<script>alert("Failed to write order details to ' . $file . '");</script>';
        }
    }
*/
?>