<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/user_orders.css">
    <title>Document</title>
    <style>
body {
    background-color: #F8F7F1;
}
/* Styles for tabs */
.tab {
    display: none;
}

/* Style for active tab */
.tab.active {
    display: block;
}

/* Style for tab buttons */
.tab-container {
    width: 80%;
    margin: 1% auto 0 auto;
    display: flex; /* Use flexbox */
    justify-content: space-evenly; /* Evenly space the buttons */
    border-bottom: 20px solid #FFDC5C;
}

.tab-button {
    cursor: pointer;
    padding: 10px 15px;
    border: none;
    background-color: #F8F7F1;
    border-radius: 5px 5px 0 0;
    font-size: 25px;
    font-family: 'Playfair Display';
    font-weight: 700;
    margin-right: 10px;
}

.item-box {
    cursor: pointer;
}

/* Style for active tab button */
.tab-button.active {
    background-color: #FFDC5C;
}

.order-header {
    margin-left: 50px;
    margin-top: 70px;
    margin: 70px 0 20px 50px;
    font-size: 36px;
    font-weight: 700;
}

form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
input[type="file"] {
    margin-bottom: 10px;
}
input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
input[type="submit"]:hover {
    background-color: #45a049;
}
/* tab 4 */
.order-container {
    width: 90%;
    height: 200px;
    background-color: #FDFDFD;
    box-shadow: 0px 4px 4px 0px #00000040;
}
.order-container-space {
    height: 20px; /* Adjust this value as needed */
}


.status-header {
    font-family: Playfair Display;
    margin: 30px 0 0 0 ;
    font-size: 20px;
    font-weight: 700;
    line-height: 26.66px;
    text-align: left;

}
.item-description {
    font-family: Inter;
    font-size: 22px;
    font-weight: 500;
    line-height: 24px;
    text-align: left;
    width: 200px;
}
.price-to-pay {
    font-family: Inter;
    font-size: 22px;
    font-weight: 500;
    line-height: 24px;
    text-align: left;
    width: 200px;
}
.deliver-pickup-address {
    font-family: Inter;
    font-size: 22px;
    font-weight: 500;
    line-height: 30px;
    text-align: left;
    width: 300px;
}
.order-type {
    font-family: Inter;
    font-size: 22px;
    font-weight: 500;
    line-height: 30px;
    text-align: left;
    width: 150px;
}
.order-status {
    font-family: Inter;
    font-size: 22px;
    font-weight: 500;
    line-height: 30px;
    text-align: left;
    width: 150px;
}
.img-order {
    width: 200px;
    height: 150px;
}

table {
    width: 100%;
    border-collapse: collapse;
}
td {
    padding: 2%;
}
th {
    text-align: left !important;
    padding: 2%;
}
/* tab 5 */
.tabLabels {
    width: 90%;
    margin: 30px auto 0 auto;
}
#fileInput {
            display: none; /* Hide the file input */
        }
        #addAttachmentButton {
            cursor: pointer;
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f0f0f0;
        }
/* tab 7 */
.received-button {
    font-family: Playfair Display;
    font-size: 20px;
    font-weight: 700;
    line-height: 26.66px;
    text-align: left;
    background-color: violet;
    cursor: pointer;
    padding: 20px 25px;
    border: none;
    background-color: #F8F7F1;
    border-radius: 5px 5px 0 0;
    font-size: 25px;
    font-family: 'Playfair Display';
    font-weight: 700;
    box-shadow: 0px 4px 4px 0px #00000040;
    background-color: #D9D9D9;
    border-radius: 25px;
}
    </style>
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
        <button class="tab-button" onclick="openTab(event, 'tab5')">To be delivered</button>
        <button class="tab-button" onclick="openTab(event, 'tab6')">On Delivery</button>
        <button class="tab-button" onclick="openTab(event, 'tab7')">Received</button>
        <button class="tab-button" onclick="openTab(event, 'tab8')">Cancelled</button>
    </div>
</div>


<!-- Tab content -->
<div id="tab-content">
    <div id="tab1" class="tab active">
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
    $sql = "SELECT furniture_type, quoted_price, del_address, order_type, is_accepted FROM orders";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the table header outside the loop
        echo '
        <table class="tabLabels">
            <tr class="status-header">
                <th>Item</th>
                <th>Item description</th>
                <th>Quoted Price</th>
                <th>Deliver and pick-up address</th>
                <th>Order type</th>
                <th>Status</th> 
            </tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Output the table rows inside the loop with additional classes for styling
            echo '
            <tr class="order-container">
                <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                <td><div class="price-to-pay"><p>' ."₱". $row["quoted_price"] . '</p></div></td>
                <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
                <td><div class="order-type"><p>' . $row["order_type"] . '</p></div></td>
                <td><div class="order-status"><p>' . $row["is_accepted"] . '</p></div></td>
            </tr>';
            // Add spacing between order-container elements
            echo '<tr class="order-container-space"><td colspan="6"></td></tr>';
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
    $sql = "SELECT furniture_type, quoted_price, del_address, order_type FROM orders WHERE is_accepted = 'pending'    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the table header outside the loop
        echo '
        <table class="tabLabels">
            <tr class="status-header">
                <th>Item</th>
                <th>Item description</th>
                <th>Price to pay</th>
                <th>Deliver and pick-up address</th>
                <th>Order type</th>
            </tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Output the table rows inside the loop with additional classes for styling
            echo '
            <tr class="order-container">
                <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                <td><div class="price-to-pay"><p>' ."₱". $row["quoted_price"] . '</p></div></td>
                <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
                <td><div class="order-type"><p>' . $row["order_type"] . '</p></div></td>
            </tr>';
            // Add spacing between order-container elements
            echo '<tr class="order-container-space"><td colspan="6"></td></tr>';
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
        $sql = "SELECT furniture_type, quoted_price, del_address FROM orders WHERE order_status ='pending_downpayment'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output the table header outside the loop
            echo '
            <table class="tabLabels">
                <tr class="status-header">
                    <th>Item</th>
                    <th>Item description</th>
                    <th>Quoted Price</th>
                    <th>Deliver and pick-up address</th>
                    <th>Proof of payment</th>
                </tr>';

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Output the table rows inside the loop with additional classes for styling
                echo '
                <tr class="order-container">
                    <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                    <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                    <td><div class="price-to-pay"><p>' ."₱". $row["quoted_price"] . '</p></div></td>
                    <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
                    <td><div class="received-status"><button id="addAttachmentButton">Add Attachments</button><input type="file" id="fileInput" multiple></div></td>
                </tr>';
                // Add spacing between order-container elements
                echo '<tr class="order-container-space"><td colspan="6"></td></tr>';
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
    $sql = "SELECT furniture_type, quoted_price, del_address, order_type FROM orders WHERE is_accepted = 'pending'    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the table header outside the loop
        echo '
        <table class="tabLabels">
            <tr class="status-header">
                <th>Item</th>
                <th>Item description</th>
                <th>Price to pay</th>
                <th>Deliver and pick-up address</th>
                <th>Received</th>
            </tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Output the table rows inside the loop with additional classes for styling
            echo '
            <tr class="order-container">
                <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                <td><div class="price-to-pay"><p>' ."₱". $row["quoted_price"] . '</p></div></td>
                <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
                <td><div class="order-type"><p><button class="received-button">Received</button></p></div></td>
            </tr>';
            // Add spacing between order-container elements
            echo '<tr class="order-container-space"><td colspan="6"></td></tr>';
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
    $sql = "SELECT furniture_type, quoted_price, del_address, order_type FROM orders WHERE is_accepted = 'pending'    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the table header outside the loop
        echo '
        <table class="tabLabels">
            <tr class="status-header">
                <th>Item</th>
                <th>Item description</th>
                <th>Price to pay</th>
                <th>Deliver and pick-up address</th>
                <th>Received</th>
            </tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Output the table rows inside the loop with additional classes for styling
            echo '
            <tr class="order-container">
                <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                <td><div class="price-to-pay"><p>' ."₱". $row["quoted_price"] . '</p></div></td>
                <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
                <td><div class="order-type"><p><button class="received-button">Click Here to review</button></p></div></td>
            </tr>';
            // Add spacing between order-container elements
            echo '<tr class="order-container-space"><td colspan="6"></td></tr>';
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
    $sql = "SELECT furniture_type, quoted_price, del_address, order_type FROM orders WHERE order_status = 'cancelled'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the table header outside the loop
        echo '
        <table class="tabLabels">
            <tr class="status-header">
                <th>Item</th>
                <th>Item description</th>
                <th>Price to pay</th>
                <th>Deliver and pick-up address</th>
            </tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Output the table rows inside the loop with additional classes for styling
            echo '
            <tr class="order-container">
                <td><img src="websiteimages/carouselimg2.jpg" alt="" class="img-order"></td>
                <td><div class="item-description"><p>' . $row["furniture_type"] . '</p></div></td>
                <td><div class="price-to-pay"><p>' ."₱". $row["quoted_price"] . '</p></div></td>
                <td><div class="deliver-pickup-address"><p>' . $row["del_address"] . '</p></div></td>
            </tr>';
            // Add spacing between order-container elements
            echo '<tr class="order-container-space"><td colspan="6"></td></tr>';
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
</div>
<script>
// Function to open a specific tab
function openTab(event, tabName) {
    // Hide all tabs
    var tabs = document.getElementsByClassName("tab");
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove("active");
    }

    // Deactivate all tab buttons
    var tabButtons = document.getElementsByClassName("tab-button");
    for (var i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }

    // Show the selected tab
    document.getElementById(tabName).classList.add("active");

    // Activate the selected tab button
    event.currentTarget.classList.add("active");
}

// Open the first tab by default
document.getElementById("tab1").classList.add("active");
document.getElementsByClassName("tab-button")[0].classList.add("active");

//proof-payment-form
document.getElementById('attachmentForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    var formData = new FormData(); // Create a FormData object
    var fileInput = document.getElementById('fileInput'); // Get the file input element
    var files = fileInput.files; // Get the selected files

    // Check if files are selected
    if (files.length > 0) {
        // Append the files to FormData
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            formData.append('attachments[]', file, file.name);
        }

        // You can send the FormData to the server using AJAX or submit the form
        // For demonstration, let's just log the FormData
        console.log(formData);
    } else {
        alert('Please select a file.');
    }
});

// add attachment button 
document.getElementById('addAttachmentButton').addEventListener('click', function() {
            document.getElementById('fileInput').click(); // Trigger the click event of the file input
        });         
</script>
</body>
</html>
