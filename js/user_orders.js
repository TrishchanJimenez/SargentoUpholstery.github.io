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
document.getElementById('attachmentForm').addEventListener('submit', function (event) {
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

//file upload
function handleFileUpload(orderId) {
    var fileInput = document.getElementById('fileUpload' + orderId);
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file', file);
    formData.append('order_id', orderId);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/api/upload_proof_of_payment.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('File uploaded successfully!');
        } else {
            alert('An error occurred while uploading the file.');
        }
    };
    xhr.send(formData);
}

//received-button js
var button = document.getElementById("received-button");

// Add an event listener to the button
button.addEventListener("click", function () {
    // Functionality to execute when the button is clicked
    alert("Button clicked!");
});