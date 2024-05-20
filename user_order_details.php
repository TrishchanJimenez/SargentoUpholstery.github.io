<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/user_order_details.css">
</head>
<body>
<?php include_once("header.php"); ?>
    <div class="order-details-header">
        <h2>ORDER DETAILS</h2>
    </div>
    <div class="order-details-container">
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

// Check if the order-id is set in the URL
if (isset($_GET['order-id'])) {
    $order_id = intval($_GET['order-id']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT order_type FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $order_type = $order['order_type'];
    $stmt->close();

    // Query to select data for the specific order
    $sql = "SELECT O.*, order_date.*, users.*, payment.*
            FROM orders AS O
            JOIN $order_type USING(order_id)
            JOIN order_date USING(order_id)
            JOIN users USING(user_id)
            JOIN payment USING(order_id)
            WHERE O.order_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output the order details
        while ($row = $result->fetch_assoc()) {
            echo '
            <table class="tg">
                <thead>
                <tr>
                    <tr class="row1">ORDER ID</th>
                    <tr class="row1">ORDER TYPE</th>
                    <tr class="row1">FURNITURE TYPE</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="row1-content"><p>' . $row["order_id"] . '</p></td>
                    <td class="row1-content"><p>' . $row["order_type"] . '</p></td>
                    <td class="row1-content"><p>' . $row["furniture_type"] . '</p></td>
                </tr>
                </tbody>
                </table>
                <table class="tg">
                <thead>
                <tr>
                    <td class="row">ORDER PLACEMENT DATE</td>
                    <td class="row">ESTIMATED DELIVERY DATE</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="content"><p>' .$row["placement_date"]. '</p></td>
                    <td class="content"><p>' .$row["est_completion_date"]. '</p></td>
                </tr>
                <tr>
                    <td class="row">DELIVERY ADDRESS</td>
                    <td class="row">QUOTED PRICE</td>
                </tr>
                <tr>
                    <td class="content"><p>' . $row["del_address"] . '</p></td>
                    <td class="content"><p>₱' . $row["quoted_price"] . '</p></td>
                </tr>
                <tr>
                    <td class="row">PAYMENT STATUS</td>
                    <td class="row">ORDER STATUS</td>
                </tr>
                <tr>
                    <td class="content"><p>' . $row["payment_status"] . '</p></td>
                    <td class="content"><p>' . $row["order_status"] . '</p></td>
                </tr>
                </tbody>
                </table>';
        }
    } else {
        echo "<p>No details found for the specified order.</p>";
    }
    $stmt->close();
} else {
    echo "<p>No order ID specified.</p>";
}

// Close connection
$conn->close();
?>
    </div>
</body>
</html>
