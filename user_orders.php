<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="css/global.css">
      <link rel="stylesheet" href="css/user_orders.css">
      <script src="js/user_orders.js"></script>
      <title>Document</title>
   </head>
   <body>
      <?php include_once("header.php") ?>
      <div class="order-header">
         <p>My Orders</p>
      </div>
      <!-- Tab buttons -->
      <div class="tab-container">
         <div id="tab-buttons">
            <button class="tab-button" onclick="openTab(event, 'tab1')">All Orders</button>
            <button class="tab-button" onclick="openTab(event, 'tab2')">Pending</button>
            <button class="tab-button" onclick="openTab(event, 'tab3')">Ready For Pickup</button>
            <button class="tab-button" onclick="openTab(event, 'tab4')">In Production</button>
            <button class="tab-button" onclick="openTab(event, 'tab5')">To be delivered</button>
            <button class="tab-button" onclick="openTab(event, 'tab6')">On Delivery</button>
            <button class="tab-button" onclick="openTab(event, 'tab7')">Received</button>
            <button class="tab-button" onclick="openTab(event, 'tab8')">Cancelled</button>
            <button class="tab-button" onclick="openTab(event, 'tab9')">Rejected</button>
         </div>
      </div>
      <!-- Tab content -->
      <!--all orders -->
      <div id="tab-content">
         <div id="tab1" class="tab active">
            <?php
               $user_id = $_SESSION['user_id'];
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
                  
                  // Query to select data from the database
                  $sql = "SELECT order_id,furniture_type, quoted_price, del_address, order_type, is_accepted, order_status FROM orders WHERE user_id = $user_id";
                  $result = $conn->query($sql);
                  
                  
                  if ($result->num_rows > 0) {
                      // Output the table header outside the loop
                      echo '
                      <table class="tabLabels">
                          <tr class="status-header">
                              <th>Item description</th>
                              <th>Quoted Price</th>
                              <th>Delivery address</th>
                              <th>Order type</th>
                              <th>Status</th> 
                              <th>Details</th>
                          </tr>';
                  
                      // Output data of each row
                      while ($row = $result->fetch_assoc()) {
                        $prod_status = str_replace("_", "-", $row['order_status']);
                    $prod_status_text = ucwords(str_replace("-", " ", $prod_status));
                        $display_status = '';
                        if($row['order_status'] === 'pending_fullpayment'){
                            $display_status = 'Waiting For Verification';
                        }else{
                            if($row['order_status'] === 'ready_for_pickup'){
                                $display_status ='Ready For Pickup';
                            }else{
                                if ($row['is_accepted'] == 'rejected') {
                                    $display_status = 'Rejected';
                                } else {
                                    if ($row['order_status'] === 'received') {
                                        $display_status = 'Received';
                                    } else {
                                        if($row['order_status'] === 'in_production'){
                                            $display_status = 'In Production';
                                        }else{
                                            if ($row['is_accepted'] === 'pending') {
                                                $display_status = 'Pending';
                                            } else if ($row['is_accepted'] === 'accepted') {
                                                $display_status = 'Accepted';
                                            } else {
                                                if ($row['is_cancelled'] == 0) {
                                                    $display_status = htmlspecialchars($prod_status_text);
                                                } else {
                                                    $display_status = 'Cancelled';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                          echo '
                          <!DOCTYPE html>
                           <html lang="en">
                           <head>
                               <meta charset="UTF-8">
                               <meta name="viewport" content="width=device-width, initial-scale=1.0">
                               <title>Document</title>
                           </head>
                           <body>
                               <tr class="order-container" data-id="' . $row["order_id"] . '">
                              <td><div class="tab-table"><p>' . $row["furniture_type"] . '</p></div></td>
                              <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                              <td><div class="tab-table"><p>' . $row["del_address"] . '</p></div></td>
                              <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                              <td><div class="tab-table"><p>' . $display_status . '</p></div></td>
                              <td class="myTable">
                                  <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                          <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                      </svg>
                                  </a>
                              </td>
                          </tr>  
                           </body>
                           </html>
                          ';
                          // Add spacing between order-container elements
                          echo '<tr class="order-container-space"></tr>';
                      }
                  
                      // Close the table outside the loop
                      echo '</table>';
                  } else {
                      echo "0 results";
                  }
                  // Close connection
                  $conn->close();
                  ?>
         </div>
         <!-- pending -->
         <div id="tab2" class="tab">
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
               
               // Query to select data from the database
               $sql = "SELECT order_id,furniture_type, quoted_price, del_address, order_type, is_accepted, order_status FROM orders WHERE user_id = $user_id AND is_accepted = 'pending'";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                // Output the table header outside the loop
                echo '
                <table class="tabLabels">
                    <tr class="status-header">
                        <th>Item description</th>
                        <th>Quoted Price</th>
                        <th>Delivery address</th>
                        <th>Order type</th>
                        <th>Status</th> 
                        <th>Details</th>
                    </tr>';
               
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                 $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                    // Output the table rows inside the loop with additional classes for styling
                    echo '
                    <!DOCTYPE html>
                     <html lang="en">
                     <head>
                         <meta charset="UTF-8">
                         <meta name="viewport" content="width=device-width, initial-scale=1.0">
                         <title>Document</title>
                     </head>
                     <body>
                         <tr class="order-container" data-id="' . $row["order_id"] . '">
                        <td><div class="tab-table"><p>' . $row["furniture_type"] . '</p></div></td>
                        <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                        <td><div class="tab-table"><p>' . $row["del_address"] . '</p></div></td>
                        <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                        <td><div class="tab-table"><p>' . $display_status . '</p></div></td>
                        <td class="myTable">
                            <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                    <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>  
                     </body>
                     </html>
                    ';
                    // Add spacing between order-container elements
                    echo '<tr class="order-container-space"></tr>';
                }
               
                // Close the table outside the loop
                echo '</table>';
               } else {
                echo "0 results";
               }
               // Close connection
               $conn->close();
               ?>
         </div>
        
         <!--  ready for pickup -->
         <div id="tab3" class="tab">
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
               
               // Query to select data from the database
               $sql = "SELECT order_id,furniture_type, quoted_price, del_address, order_type, is_accepted, order_status FROM orders WHERE user_id = $user_id AND order_status = 'ready_for_pickup'";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                // Output the table header outside the loop
                echo '
                <table class="tabLabels">
                    <tr class="status-header">
                        <th>Item description</th>
                        <th>Quoted Price</th>
                        <th>Delivery address</th>
                        <th>Order type</th>
                        <th>Details</th>
                    </tr>';
               
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                 $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                    // Output the table rows inside the loop with additional classes for styling
                    echo '
                    <!DOCTYPE html>
                     <html lang="en">
                     <head>
                         <meta charset="UTF-8">
                         <meta name="viewport" content="width=device-width, initial-scale=1.0">
                         <title>Document</title>
                     </head>
                     <body>
                         <tr class="order-container" data-id="' . $row["order_id"] . '">
                        <td><div class="tab-table"><p>' . $row["furniture_type"] . '</p></div></td>
                        <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                        <td><div class="tab-table"><p>' . $row["del_address"] . '</p></div></td>
                        <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                        <td class="myTable">
                            <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                    <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>  
                     </body>
                     </html>
                    ';
                    // Add spacing between order-container elements
                    echo '<tr class="order-container-space"></tr>';
                }
               
                // Close the table outside the loop
                echo '</table>';
               } else {
                echo "0 results";
               }
               // Close connection
               $conn->close();
               ?>
         </div>

         <!-- in production-->
         <div id="tab4" class="tab">
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
               
               // Query to select data from the database
               $sql = "SELECT order_id,furniture_type, quoted_price, del_address, order_type, is_accepted, order_status FROM orders WHERE user_id = $user_id AND order_status = 'in_production'";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                // Output the table header outside the loop
                echo '
                <table class="tabLabels">
                    <tr class="status-header">
                        <th>Item description</th>
                        <th>Quoted Price</th>
                        <th>Delivery address</th>
                        <th>Order type</th>
                        <th>Details</th>
                    </tr>';
               
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                 $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                    // Output the table rows inside the loop with additional classes for styling
                    echo '
                    <!DOCTYPE html>
                     <html lang="en">
                     <head>
                         <meta charset="UTF-8">
                         <meta name="viewport" content="width=device-width, initial-scale=1.0">
                         <title>Document</title>
                     </head>
                     <body>
                         <tr class="order-container" data-id="' . $row["order_id"] . '">
                        <td><div class="tab-table"><p>' . $row["furniture_type"] . '</p></div></td>
                        <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                        <td><div class="tab-table"><p>' . $row["del_address"] . '</p></div></td>
                        <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                        <td class="myTable">
                            <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                    <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>  
                     </body>
                     </html>
                    ';
                    // Add spacing between order-container elements
                    echo '<tr class="order-container-space"></tr>';
                }
               
                // Close the table outside the loop
                echo '</table>';
               } else {
                echo "0 results";
               }
               // Close connection
               $conn->close();
               ?>
         </div>
         <!-- to be delivered -->
         <div id="tab5" class="tab">
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
               
               // Query to select data from the database
               $sql = "SELECT order_id, order_type, furniture_type, quoted_price, del_address FROM orders WHERE order_status ='pending_fullpayment' AND user_id = $user_id";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                   // Output the table header outside the loop
                   echo '
                   <table class="tabLabels">
                       <tr class="status-header">
                           <th>Item description</th>
                           <th>Quoted Price</th>
                           <th>Delivery address</th>
                           <th>Order type</th>
                           <th>Proof of payment</th>
                           <th>Details</th>
                       </tr>';
               
                   // Output data of each row
                   while ($row = $result->fetch_assoc()) {
                       // Check if a file is already attached
                       $fileUploaded = false; // Initialize the variable
                       // Your logic to check if a file is already uploaded goes here
                       // Query to check if a file is uploaded for the current order
               $fileUploaded = false; // Initialize the variable
               
               // Query to check if a file is uploaded for the current order
                $attachmentQuery = "SELECT fullpayment_img FROM payment WHERE order_id = " . $row["order_id"];
                $attachmentResult = $conn->query($attachmentQuery);
                
                // Check if any attachments exist for the current order
                if ($attachmentResult->num_rows > 0) {
                // Set $fileUploaded to true if attachments exist
                $fileUploaded = true;
               }
               
                       // Output the table rows inside the loop with additional classes for styling
                       echo '
                       <tr class="order-container" data-id="' . $row["order_id"] . '">
                           <td><div class="tab-table"><p>' . $row["furniture_type"] . '</p></div></td>
                           <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                           <td><div class="tab-table"><p>' . $row["del_address"] . '</p></div></td>
                           <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                           <td>';
                       
                       // If a file is uploaded, display "Waiting for verification"
                       if ($fileUploaded) {
                           echo '<p>Waiting for verification</p>';
                       } else {
                           // If no file is uploaded, display the file upload button
                           echo '
                           <div class="tab-table">
                               <button class="file-upload-button" onclick="document.getElementById(\'fileUpload' . $row["order_id"] . '\').click();">Upload File</button>
                               <input type="file" id="fileUpload' . $row["order_id"] . '" class="file-upload-input" style="display:none;" onchange="handleFileUpload(' . $row["order_id"] . ')">
                           </div>';
                       }
                       
                       echo '</td>
                           <td class="tab-table">
                               <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                       <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                   </svg>
                               </a>
                           </td>
                       </tr>';
                       // Add spacing between order-container elements
                       echo '<tr class="order-container-space"></tr>';
                   }
               
                   // Close the table outside the loop
                   echo '</table>';
               } else {
                   echo "0 results";
               }
               // Close connection
               $conn->close();
               ?>
         </div>
         <!--on delivery -->
         <div id="tab6" class="tab">
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

               // Query to select data from the database
               $sql = "SELECT order_id,furniture_type, order_type, quoted_price, del_address, order_type FROM orders WHERE order_status = 'out_for_delivery' AND user_id = $user_id ";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                   // Output the table header outside the loop
                   echo '
                   <table class="tabLabels">
                       <tr class="status-header">
                           <th>Item description</th>
                           <th>Price to pay</th>
                           <th>Delivery address</th>
                           <th>Order type</th>
                           <th>Received</th>
                           <th>Details</th>
                       </tr>';
               
                   // Output data of each row
                   while ($row = $result->fetch_assoc()) {
                       // Output the table rows inside the loop with additional classes for styling
                       echo '
                       <tr class="order-container" data-id="' . $row["order_id"] . '">
                           <td>
                               <div class="tab-table">
                                   <p>' . $row["furniture_type"] . '</p>
                               </div>
                           </td>
                           <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                           <td>
                               <div class="tab-table">
                                   <p>' . $row["del_address"] . '</p>
                               </div>
                           </td>
                           <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                           <td>
                               <div class="tab-table">
                                   <form action="update_order.php" method="post">
                                       <input type="hidden" name="order_id" value="' . $row["order_id"] . '">
                                       <button type="submit" class="received-button">Received</button>
                                   </form>
                           </div>
                           </td>
                           <td class="tab-table">
                               <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                       <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                   </svg>
                               </a>
                           </td>
                       </tr>';
               
                       // Add spacing between order-container elements
                       echo '<tr class="order-container-space"></tr>';
                       
                   }
               
                   // Close the table outside the loop
                   echo '</table>';
               } else {
                   echo "0 results";
               }
               // Close connection
               $conn->close();
               ?>
         </div>
         <!-- Received -->
         <div id="tab7" class="tab">
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
               
               // Query to select data from the database
               $sql = "SELECT order_id,furniture_type, order_type, quoted_price, del_address FROM orders WHERE order_status ='received' AND user_id = $user_id"; 
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                   // Output the table header outside the loop
                   echo '
                   <table class="tabLabels">
                       <tr class="status-header">
                           <th>Item description</th>
                           <th>Quoted Price</th>
                           <th>Delivery address</th>
                           <th>Order type</th>
                           <th>Review</th>
                           <th>Details</th>
                       </tr>';
               
                   // Output data of each row
                   while ($row = $result->fetch_assoc()) {
                       // Output the table rows inside the loop with additional classes for styling
                       echo '
                       <tr class="order-container" data-id="' . $row["order_id"] . '">
                           <td>
                               <div class="tab-table">
                                   <p>' . $row["furniture_type"] . '</p>
                               </div>
                           </td>
                           <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                           <td>
                               <div class="tab-table">
                                   <p>' . $row["del_address"] . '</p>
                               </div>
                           </td>
                           <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                           <td>
                               <div class="tab-table">
                                   <form action="review/Review_Submission.php" method="post">
                                       <input type="hidden" name="order_id" value="' . $row["order_id"] . '">
                                       <button type="submit" class="review-button">Review</button>
                                   </form>
                               </div>
                           </td>
                           <td class="chevron-right">
                               <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                       <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                   </svg>
                               </a>
                           </td>
                       </tr>';
                       // Add spacing between order-container elements
                       echo '<tr class="order-container-space"></tr>';
                   }
               
                   // Close the table outside the loop
                   echo '</table>';
               } else {
                   echo "0 results";
               }
               // Close connection
               $conn->close();
               ?>
         </div>
         <!-- cancelled -->
         <div id="tab8" class="tab">
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
               
               // Query to select data from the database
               $sql = "SELECT furniture_type, quoted_price, del_address, order_type FROM orders WHERE order_status = 'cancelled' AND user_id = $user_id";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                   // Output the table header outside the loop
                   echo '
                   <table class="tabLabels">
                       <tr class="status-header">
                           <th>Item</th>
                           <th>Item description</th>
                           <th>Price to pay</th>
                           <th>Delivery address</th>
                       </tr>';
               
                   // Output data of each row
                   while ($row = $result->fetch_assoc()) {
                       // Output the table rows inside the loop with additional classes for styling
                       echo '
                       <tr class="order-container">
                           <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                           <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                           <td><div class="price-to-pay"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>                           <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
                       </tr>';
                       // Add spacing between order-container elements
                       echo '<tr class="order-container-space"></tr>';
                   }
               
                   // Close the table outside the loop
                   echo '</table>';
               } else {
                   echo "0 results";
               }
               // Close connection
               $conn->close();
               ?> 
         </div>
         <div id="tab9" class="tab">
            <?php
               $user_id = $_SESSION['user_id'];
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
               
               // Query to select data from the database
               $sql = "SELECT order_id, furniture_type, quoted_price, del_address, order_type, is_accepted, order_status FROM orders WHERE user_id = $user_id AND is_accepted = 'rejected'";
               $result = $conn->query($sql);
               
               if ($result->num_rows > 0) {
                   // Output the table header outside the loop
                   echo '
                   <table class="tabLabels">
                       <tr class="status-header">
                           <th>Item description</th>
                           <th>Quoted Price</th>
                           <th>Delivery address</th>
                           <th>Order type</th>
                           <th>Status</th> 
                           <th>Details</th>
                       </tr>';
               
                   // Output data of each row
                   while ($row = $result->fetch_assoc()) {
                       $display_status = 'Rejected';
                       echo '
                       <tr class="order-container" data-id="' . $row["order_id"] . '">
                           <td><div class="tab-table"><p>' . $row["furniture_type"] . '</p></div></td>
                           <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                           <td><div class="tab-table"><p>' . $row["del_address"] . '</p></div></td>
                           <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                           <td><div class="tab-table"><p>' . $display_status . '</p></div></td>
                           <td class="myTable">
                               <a href="user_order_details.php?order-id=' . $row["order_id"] . '">
                                   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                       <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                   </svg>
                               </a>
                           </td>
                       </tr>';
                       echo '<tr class="order-container-space"></tr>';
                   }
                   echo '</table>';
               } else {
                   echo "0 results";
               }
               $conn->close();
               ?>
      </div>
   </body>
</html>