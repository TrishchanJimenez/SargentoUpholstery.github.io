<?php
    include_once("../database_connection.php");

    $order_id = $_GET['order-id'];
    try {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT order_type, is_cancelled, is_accepted, order_status, order_id, refusal_reason, is_cancelled FROM orders WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_type = $order['order_type'];

        $query = "
            SELECT 
                *
            FROM 
                (
                    SELECT 
                        *
                    FROM 
                        orders
                    WHERE 
                        order_id = :order_id
                ) 
                AS O
            JOIN 
                order_date USING(order_id)
            JOIN 
                users USING(user_id)
            JOIN 
                payment USING(order_id) 
            LEFT JOIN 
                pickup USING(order_id)
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $order_details = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "
            SELECT 
                *
            FROM  
                addresses a
                    INNER JOIN
                orders o
                    ON a.address_id = :del_address_id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':del_address_id', $order_details['del_address_id'], PDO::PARAM_INT);
        $stmt->execute();
        $address = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order_details) {
            die("Order details not found.");
        }

        $prod_status = str_replace("_", "-", $order_details['order_status']);
        $prod_status_text = ucwords(str_replace("-", " ", $prod_status));

        $payment_status = str_replace("_", "-", $order_details['payment_status']);
        $payment_status_text = ucwords(str_replace("_", " ", $order_details['payment_status']));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/user_order_details.css">
    <link rel="stylesheet" href="/css/review_submission.css">
</head>
<body>
    <?php include_once("../header.php"); ?>
    <a href="javascript:void(0);" onclick="goBack()">
        <img src="\websiteimages\back.png" alt="Go Back" class="back-button-img">
    </a>
    <div class="order-details-header">
        <h2>ORDER DETAILS</h2>
    </div>
    <div class="container">
        <div class="order-details-container-left">
            <div class="order-details-container-header">
                <h2>ORDER INFORMATION</h2>
            </div>
            <table class="left-table">
                <tr>
                    <th class="row">ORDER ID</th>
                    <th class="row">ORDER TYPE</th>
                </tr>
                <tr>
                    <td class="content">
                        <p><?php echo htmlspecialchars($order_id); ?></p>
                    </td> <!-- Replaced $order with $order_id -->
                    <td class="content">
                        <p>
                            <?php
                                if ($order_type === "mto") 
                                    echo "MTO"; // Changed $order['order_type'] to $order_type
                                else 
                                    echo "Repair"
                            ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th class="row">ORDER PLACEMENT DATE</th>
                    <th class="row">ESTIMATED DELIVERY DATE</th>
                </tr>
                <tr>
                    <td class="content">
                        <p><?= date('M d, Y', strtotime($order_details['placement_date'])) ?></p>
                    </td> <!-- Fixed $order to $order_details -->
                    <td class="content">
                        <p>
                            <?php
                                if ($order_details['est_completion_date'] === '0000-00-00') { // Fixed $order to $order_details
                                    echo "N/A";
                                } else {
                                    echo date('M d, Y', strtotime($order_details['est_completion_date'])); // Fixed $order to $order_details
                                }
                            ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th class="row">DELIVERY ADDRESS</th>
                    <th class="row">QUOTED PRICE</th>
                </tr>
                <tr>
                    <td class="content">
                        <p><?php echo htmlspecialchars($address['address']); ?></p>
                    </td> <!-- Fixed $order to $order_details -->
                    <td class="content">
                        <p> 
                            <?php
                                if (is_null($order_details['quoted_price'])) 
                                    echo "N/A"; // Fixed $order to $order_details
                                else 
                                    echo "₱" . $order_details['quoted_price'];
                            ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th class="row">PAYMENT STATUS</th>
                    <th class="row">ORDER STATUS</th>
                </tr>
                <tr>
                    <td class="content">
                        <p><?php echo htmlspecialchars($payment_status_text); ?></p>
                    </td>
                    <td class="content">
                        <p>
                            <?php
                                if ($order['is_cancelled'] == 1) {
                                    echo 'Cancelled';
                                } else {
                                    if ($order['order_status'] === 'pending_fullpayment') {
                                        echo 'Waiting For Verification';
                                    } else {
                                        if ($order['order_status'] === 'ready_for_pickup') {
                                            echo 'Ready For Pickup';
                                        } else {
                                            if ($order['is_accepted'] == 'rejected') {
                                                echo 'Rejected';
                                            } else {
                                                if ($order['order_status'] === 'received') {
                                                    echo 'Received';
                                                } else {
                                                    if ($order['order_status'] === 'in_production') {
                                                        echo 'In Production';
                                                    } else {
                                                        if ($order['is_accepted'] === 'pending') {
                                                            echo 'Pending';
                                                        } else if ($order['is_accepted'] === 'accepted') {
                                                            echo 'Accepted';
                                                        } else {
                                                            if ($order['is_cancelled'] === 0) {
                                                                echo htmlspecialchars($prod_status_text);
                                                            } else {
                                                                echo 'Cancelled';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="right-side">
            <div class="order-details-container-right">
                <div class="">
                    <div class="order-details-container-header-right">
                        <h2>ORDER IMAGE</h2>
                    </div>
                    <div class="order-details-image">
                        <img src="/<?php echo $order_details['ref_img_path']; ?>" alt="Order Image">
                    </div>
                </div>
            </div>
            <?php
                if ($order['order_status'] === 'received') {
                    echo '
                        <div class="review-background">
                            <div class="review-content">
                                <div class="review-header">
                                    <h1>Rate Service</h1>
                                    <span class="close-modal" onclick="closeReviewModal()">&times;</span>
                                </div>
                                <form id="reviewForm" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="order_id" value="' . $order_details['order_id'] . '">
                                    <div class="starcontainer">
                                        <p>Rate Quality:</p>
                                        <div class="rating" id="ratingStars">
                                            <input type="hidden" id="rating" name="rating" value="">
                                            <span class="star" data-value="1">&#9733;</span>
                                            <span class="star" data-value="2">&#9733;</span>
                                            <span class="star" data-value="3">&#9733;</span>
                                            <span class="star" data-value="4">&#9733;</span>
                                            <span class="star" data-value="5">&#9733;</span>
                                        </div><br><br>
                                    </div>
                                    <label for="review">Review:</label><br>
                                    <textarea id="review" name="comment" rows="4" cols="50" placeholder="Enter review here..." required></textarea><br><br>
                                    <label for="images">Additional Images:</label>
                                    <div class="file-drop" id="imagesDrop" onclick="document.getElementById(\'images\').click();">
                                        <span>Click Here to Add Images</span>
                                        <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                                    </div>
                                    <div id="imagesPreview"></div><br><br>
                                    <input type="submit" value="Submit Review">
                                </form>
                            </div>
                        </div>
                        <div>
                            <button type="submit" value=' . $order_details['order_id'] . ' class="review-button">Review</button>
                        </div>
                    ';
                } else if ($order['is_cancelled'] == 0) {
                    echo '
                        <form action="/api/cancel_order.php" method="post">
                            <input type="hidden" name="order_id" value="' . $order["order_id"] . '">
                            <button type="submit" class="cancel-order-button">cancel order</button>
                        </form>
                    ';
                }
                // Insert the payment upload form conditionally
                if ($order['is_cancelled'] == 0 && $order['is_accepted'] !== 'rejected') {
                    if ($order_details['payment_status'] === 'pending_downpayment' || $order_details['payment_status'] === 'pending_fullpayment') {
                        echo $formHtml;
                    }
                }
            ?>
            <?php
                if ($order_details['fullpayment_img'] !== null || $order_details['downpayment_img'] !== null) {
                    echo 'Proof Of Payment Already Uploaded';
                }else{
                    include_once('../api/upload_proof_of_payment.php');
                // Check if the order is not cancelled or rejected
                if ($order_details['is_cancelled'] == 0 && $order_details['is_accepted'] !== 'rejected') {
                    // Determine the payment status to show the appropriate form
                    $uploadType = '';
                    $formTitle = '';
                    if ($order_details['order_status'] === 'pending_downpayment') {
                        $uploadType = 'downpayment';
                        $formTitle = 'UPLOAD PROOF OF DOWNPAYMENT';
                    } elseif ($order_details['order_status'] === 'pending_fullpayment') {
                        $uploadType = 'fullpayment';
                        $formTitle = 'UPLOAD PROOF OF REMAINING BAL.';
                    }

                    if ($uploadType !== '') {
                        echo '
                            <div class="payment-upload-form">
                                <div class="order-details-container-header-right">
                                    <h2>' . $formTitle . '</h2>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="order_id" value="' . htmlspecialchars($order_id) . '">
                                    <input type="hidden" name="upload_type" value="' . $uploadType . '">
                                    <label for="payment_method">Select Payment Method:</label>
                                    <div class="payment-methods">
                                        <label>
                                            <input type="radio" name="payment_method" value="gcash" required> GCash
                                        </label>
                                        <label>
                                            <input type="radio" name="payment_method" value="paymaya" required> PayMaya
                                        </label>
                                    </div>
                                    <label for="payment_image">Choose Image:</label>
                                    <input type="file" name="payment_image" accept="image/*" required>
                                    <button type="submit" name="upload_payment">Upload</button>
                                </form>
                            </div>
                        ';
                    }
                }
                }
                
            ?>
        </div>
    </div>
    <?php
        if ($order['is_accepted'] === 'rejected') {
            echo '
                <div class="rejected-reason">
                    <div class="rejected-header">
                        <h2>REFUSAL REASON</h2>
                    </div>
                    <div class="text-field">
                        <p>' . $order["refusal_reason"] . '</p>
                    </div>
                </div>
            ';
        }
    ?>
    <script src="/js/user_order_details.js"></script>
    <script src="/js/review_submission.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>