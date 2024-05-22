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
   $stmt = $conn->prepare("SELECT order_type, ref_img_path FROM orders WHERE order_id = ?");
   $stmt->bind_param("i", $order_id);
   $stmt->execute();
   $result = $stmt->get_result();
   $order = $result->fetch_assoc();
   
   if (!$order) {
       die("Order not found.");
   }
   
   $order_type = $order['order_type'];
   $image_path = $order['ref_img_path'];
   
   $query = "
       SELECT *
       FROM orders O
       JOIN $order_type T ON O.order_id = T.order_id
       JOIN order_date D ON O.order_id = D.order_id
       JOIN users U ON O.user_id = U.user_id
       JOIN payment P ON O.order_id = P.order_id
       WHERE O.order_id = ?
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
         body {
         background-color: #F8F7F1;
         }
         .back-button {
         background-color: #FFDC5C;
         color: black;
         font-family: 'Playfair Display';
         border: none;
         padding: 10px 20px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin: 10px 0 0 10px;
         cursor: pointer;
         border-radius: 5px;
         transition: background-color 0.3s;
         }
         .back-button:hover {
         background-color: #0056b3;
         }
         .container {
         display: flex;
         width: 90%;
         margin: auto;
         justify-content: space-evenly;
         }
         .order-details-header {
         font-family: 'Playfair Display';
         font-size: 30px;
         margin: 20px 0 40px 40px;
         }
         .order-details-container-left, .order-details-container-right {
         background-color: #FDFDFD;
         box-shadow: 0px 4px 4px 0px #00000040;
         padding: 20px;
         }
         .order-details-container-left {
         width: 60%;
         height: auto;
         }
         .right-side {
         width: 30%;
         }
         .order-details-container-right {
         height: auto;
         padding: 20px;
         }
         .order-details-container-header, .order-details-container-header-right {
         font-family: 'Playfair Display';
         color: #FFDC5C;
         background-color: black;
         padding: 10px;
         margin-bottom: 20px;
         }
         .left-table {
         width: 100%;
         margin: auto;
         padding: 2%;
         }
         .row {
         font-size: 25px;
         text-align: left;
         padding: 1%;
         font-family: Inter;
         }
         .content {
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
         padding: 10px 20px;
         font-size: 16px;
         cursor: pointer;
         margin: 20px 0;
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
                  <td class="content"><?php echo htmlspecialchars($order_details["order_id"]); ?></td>
                  <td class="content"><?php echo $order_details['order_type'] === "mto" ? "MTO" : "Repair"; ?></td>
               </tr>
               <tr>
                  <th class="row">ORDER PLACEMENT DATE</th>
                  <th class="row">ESTIMATED DELIVERY DATE</th>
               </tr>
               <tr>
                  <td class="content"><?php echo date('M d, Y', strtotime($order_details['placement_date'])); ?></td>
                  <td class="content"><?php echo $order_details['est_completion_date'] === '0000-00-00' ? "N/A" : date('M d, Y', strtotime($order_details['est_completion_date'])); ?></td>
               </tr>
               <tr>
                  <th class="row">DELIVERY ADDRESS</th>
                  <th class="row">QUOTED PRICE</th>
               </tr>
               <tr>
                  <td class="content"><?php echo htmlspecialchars($order_details["del_address"]); ?></td>
                  <td class="content"><?php echo is_null($order_details['quoted_price']) ? "N/A" : "₱" . htmlspecialchars($order_details['quoted_price']); ?></td>
               </tr>
               <tr>
                  <th class="row">PAYMENT STATUS</th>
                  <th class="row">ORDER STATUS</th>
               </tr>
               <tr>
                  <td class="content"><?php echo htmlspecialchars($payment_status_text); ?></td>
                  <td class="content"><?php echo htmlspecialchars($prod_status_text); ?></td>
               </tr>
            </table>
         </div>
         <div class="right-side">
            <div class="order-details-container-right">
               <div class="order-details-container-header-right">
                  <h2>ORDER IMAGE</h2>
               </div>
               <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Order Image" style="width: 100%; height: auto; border-radius: 10px;">
            </div>
            <form action="cancel_order.php" method="post">
               <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_details["order_id"]); ?>">
               <button type="submit" class="cancel-order-button">Cancel Order</button>
            </form>
         </div>
      </div>
      <script>
         function goBack() {
             window.history.back();
         }
      </script>
   </body>
</html>