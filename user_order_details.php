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
$stmt = $conn->prepare("SELECT order_type FROM orders WHERE order_id = ?");
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
    <link rel="stylesheet" href="css/global.css">
    <style>
body{
    background-color: #F8F7F1;
}
.back-button {
    background-color: #FFDC5C; /* Blue background */
    color: black; /* White text */
    font-family: 'Playfair Display';
    border: none; /* No border */
    padding: 10px 20px; /* Some padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block; /* Make the button inline */
    font-size: 16px; /* Increase font size */
    margin: 10px 0 0 10px; /* Some margin */
    cursor: pointer; /* Pointer/hand icon */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s; /* Smooth transition */
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
    </style>
</head>
<body>
<?php include_once("header.php"); ?>
<button class="back-button" onclick="goBack()">Go Back</button>

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
                    <td class="content"><p><?php echo htmlspecialchars($prod_status_text); ?></p></td>
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
    <form action="cancel_order.php" method="post">
    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>"> <!-- Fixed $row to $order_id -->
    <button type="submit" class="cancel-order-button">Cancel Order</button>
    </form>
    </div>


    </div>
    

</body>
</html>
<script>
    function goBack() {
    window.history.back();
}
</script>
