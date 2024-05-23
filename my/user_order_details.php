<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sargento_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = $_GET['order-id'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT order_type, is_cancelled, is_accepted, order_status, order_id, refusal_reason, is_cancelled FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$order_type = $order['order_type'];

$query = "
   SELECT *
   FROM (
       SELECT *
       FROM orders
       WHERE order_id = ?
   ) AS O
   JOIN order_date USING(order_id)
   JOIN users USING(user_id)
   JOIN payment USING(order_id)
   LEFT JOIN pickup USING(order_id)
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_details = $result->fetch_assoc();

if (!$order_details) {
    die("Order details not found.");
}

$prod_status = str_replace("_", "-", $order_details['order_status']);
$prod_status_text = ucwords(str_replace("-", " ", $prod_status));

$payment_status = str_replace("_", "-", $order_details['payment_status']);
$payment_status_text = ucwords(str_replace("_", " ", $order_details['payment_status']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="/css/global.css">
    <style>
body{
    background-color: #F8F7F1;
}

.back-button-img{
    width: 50px;
    margin: 25px 0 0px 30px;
}

.back-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}
.container{
    display: flex;
    width: 90%;
    margin: auto;
    justify-content: space-evenly;
}
.order-details-header{
    font-family: 'Playfair Display';
    font: bold;
    font-size: 30px;
    margin: 20px 0 40px 40px;
}
.order-details-container-left{
    width: 60%;
    height: auto; /* Changed from fixed height */
    background-color: #FDFDFD;
    box-shadow: 0px 4px 4px 0px #00000040;
}

.right-side{
    width: 30%;
}
.order-details-container-right{
    height: auto; /* Changed from fixed height */
    background-color: #FDFDFD;
    box-shadow: 0px 4px 4px 0px #00000040;
}
.order-details-container-header{
    font-family: 'Playfair Display';
    color: #FFDC5C;
    background-color: BLACK;
}
.order-details-container-header-right{
    font-family: 'Playfair Display';
    color: #FFDC5C;
    background-color: BLACK;
    padding: 3%;
}
.order-details-container-header h2{
    padding: 1.5%;
}
.left-table-top{
    width: 90%;
    margin: 20px auto 0 auto;
}
.left-table{
    width: 90%;
    margin: auto;
    padding: 2%;
}

.row{
    font-size: 25px;
    text-align: left;
    padding: 1%;
    font-family: Inter;
}
.content{
    text-align: left;
    font-size: 18px;
    padding: 1%;
    font-family: Inter;
}
.cancel-order-button {
    background-color: red;
    color: white;
    border: none;
    border-radius: 25px;
    padding: 5%;
    font-size: 30px;
    cursor: pointer;
    margin: 40px 0 0 0 ;
    font-family: Inter;
}
.rejected-reason{
    width: 50%;
    margin: 3% auto 0 auto;
    height: auto; /* Changed from fixed height */
    background-color: #FDFDFD;
    box-shadow: 0px 4px 4px 0px #00000040;
    font-family: Inter;
    font-size: 18px;
}
.rejected-header{
    font-family: 'Playfair Display';
    color: #FFDC5C;
    background-color: BLACK;
    padding: 3%;
}
.text-field{
    padding-top: 4%;
    padding-bottom: 4%;
    margin: 0 auto 0 auto;
    text-align: center;
    width: 80%;
}
.review-button{
    padding: 20px 25px;
    border: none;
    background-color: #FFDC5C;
    border-radius: 5px 5px 0 0;
    font-size: 25px;
    font-family: 'Playfair Display';
    font-weight: 700;
    box-shadow: 0px 4px 4px 0px #00000040;
    border-radius: 25px;
    margin-top: 20px ;
    }
    </style>
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
                    <td class="content"><p><?php echo htmlspecialchars($order_id); ?></p></td> <!-- Replaced $order with $order_id -->
                    <td class="content"><p><?php
                                    if($order_type === "mto") echo "MTO"; // Changed $order['order_type'] to $order_type
                                    else echo "Repair"
                                ?></p></td>
                </tr>
                <tr>
                    <th class="row">ORDER PLACEMENT DATE</th>
                    <th class="row">ESTIMATED DELIVERY DATE</th>
                </tr>
                <tr>
                    <td class="content"><p><?= date('M d, Y', strtotime($order_details['placement_date']))?></p></td> <!-- Fixed $order to $order_details -->
                    <td class="content"><p><?php
                                    if($order_details['est_completion_date'] === '0000-00-00')  { // Fixed $order to $order_details
                                        echo "N/A";
                                    } else {
                                        echo date('M d, Y', strtotime($order_details['est_completion_date'])); // Fixed $order to $order_details
                                    }
                                ?></p></td>
                </tr>
                <tr>
                    <th class="row">DELIVERY ADDRESS</th>
                    <th class="row">QUOTED PRICE</th>
                </tr>
                <tr>
                    <td class="content"><p><?php echo htmlspecialchars($order_details["del_address"]); ?></p></td> <!-- Fixed $order to $order_details -->
                    <td class="content"><p> <?php
                                    if(is_null($order_details['quoted_price'])) echo "N/A"; // Fixed $order to $order_details
                                    else echo "₱" . $order_details['quoted_price'];
                                ?></p></td>
                </tr>
                <tr>
                    <th class="row">PAYMENT STATUS</th>
                    <th class="row">ORDER STATUS</th>
                </tr>
                <tr>
                    <td class="content"><p><?php echo htmlspecialchars($payment_status_text); ?></p></td>
                    <td class="content"><p><?php 
                    if($order['is_cancelled'] == 1){
                        echo'Cancelled';
                    }else{
                        if($order['order_status'] === 'pending_fullpayment'){
                            echo'Waiting For Verification';
                        }else{
                            if($order['order_status'] === 'ready_for_pickup'){
                                echo'Ready For Pickup';
                            }else{
                                if($order['is_accepted'] =='rejected'){
                                    echo'Rejected';
                                }else{
                                    if($order['order_status'] ==='received'){
                                        echo'Received';
                                    }else{
                                        if($order['order_status'] === 'in_production'){
                                            echo'In Production';
                                        }else{
                                            if($order['is_accepted'] === 'pending'){
                                                echo'Pending';
                                            }else if($order['is_accepted'] === 'accepted'){
                                                echo'Accepted';
                                            }else{
                                                if($order['is_cancelled'] === 0){
                                                    echo htmlspecialchars($prod_status_text);
                                                }else{
                                                    echo'Cancelled';
                                                }
                                            }
                                        }
                                    }
                                } 
                            }  
                        }   
                    }
                    
                    ?></p></td>
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
                        <img src="<?php echo $image_path; ?>" alt="Order Image">
                </div>
            </div>
            </div>
            <?php 
                if($order['order_status'] === 'received'){
                    echo'
                        <form action="review/Review_Submission.php" method="post">
                           <input type="hidden" name="order_id" value="' . $order["order_id"] . '">
                           <button type="submit" class="review-button">Review</button>
                        </form>
                                   ';
                }else if($order['is_cancelled'] == 0){
                    echo'
                        <form action="/api/cancel_order.php" method="post">
                            <input type="hidden" name="order_id" value="' . $order["order_id"] .'"> <!-- Fixed $row to $order_id -->
                            <button type="submit" class="cancel-order-button">Cancel Order</button>
                        </form>';
                }
            ?>
        </div>
    </div>
    
    <?php
        if($order['is_accepted'] === 'rejected'){
            echo'<div class="rejected-reason">
                    <div class="rejected-header">
                        <h2>REFUSAL REASON</h2>
                    </div>
                    <div class="text-field">
                    <p>' . $order["refusal_reason"] . '</p>
                    </div>
            </div>';
        }
    ?>

    
</body>
</html>
<script>
    function goBack() {
    window.history.back();
}
</script>
