<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/user_orders.css">
    <title>Document</title>
    <style>
	/* Styles for tabs */
    .tab {
		display: none;
    }

    /* Style for active tab */
    .tab.active {
		display: block;
    }

    /* Style for tab buttons */
    .tab-container{
		width: 80%;
		margin: 1% auto 0 auto;
		display: flex; /* Use flexbox */
		justify-content: space-evenly; /* Evenly space the buttons */
    }

    .tab-button {
		cursor: pointer;
		padding: 10px 15px;
		border: none;
		border-bottom: 10px solid #FFDC5C;
		background-color: white;
		border-radius: 5px 5px 0 0;
		font-size: 25px;
		font-family: 'Playfair Display';
		font-weight: 700;
		margin-right: 10px;
    }

    /* Style for active tab button */
    .tab-button.active {
		background-color: #FFDC5C;
    }

    .order-header{
        margin-left: 20px;
        margin-top: 30px;
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

    /* tab 5 */
    .tabLabels{
		display: flex;
		justify-content: space-evenly;
		margin: 2% 0 0 0 ;
		font-family: 'Playfair Display';
		font-size: 20px;
		font-weight: 700;
    }
    </style>
</head>
<body>
    <?php
		include_once("header.php");
		require 'database_connection.php';
	?>

    <div class="order-header">
		<p>My Orders</p>
    </div>

    <!-- Tab buttons -->
	<div class="tab-container">
		<div id="tab-buttons">
			<button class="tab-button" onclick="openTab(event, 'tab1')">All Orders</button>
			<button class="tab-button" onclick="openTab(event, 'tab2')">Pending</button>
			<button class="tab-button" onclick="openTab(event, 'tab3')">Repair</button>
			<button class="tab-button" onclick="openTab(event, 'tab4')">Customize</button>
			<button class="tab-button" onclick="openTab(event, 'tab5')">To be delivered</button>
			<button class="tab-button" onclick="openTab(event, 'tab6')">On Delivery</button>
			<button class="tab-button" onclick="openTab(event, 'tab7')">Received</button>
			<button class="tab-button" onclick="openTab(event, 'tab8')">Cancelled</button>
		</div>
	</div>

	<!-- Tab content -->
	<div id="tab-content">
		<div id="tab1" class="tab active">Content of Tab 1</div>
		<div id="tab2" class="tab">Content of Tab 2</div>
		<div id="tab3" class="tab">Content of Tab 3</div>
		<div id="tab4" class="tab">Content of Tab 4</div>

		<div id="tab5" class="tab">
		<div class="tabLabels">
			<div class="labels">Item</div>
			<div class="labels">Item description</div>
			<div class="labels">Price to pay</div>
			<div class="labels">Delivary address</div>
			<div class="labels">Proof of payment</div>
		</div>
		<?php
			echo'
			<form id ="proof-payment-form" enctype ="multipart/form-data">
				<input type="file" id="fileInput" name="attachment" accept="image,application/pdf">
				<input type="submit" value="Upload Attachment">
			</form>
			';
		?>
		</div>
		<div id="tab6" class="tab">Content of Tab 6</div>
		<div id="tab7" class="tab">Content of Tab 7</div>
		<div id="tab8" class="tab">Content of Tab 8</div>
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
	</script>
</body>
</html>