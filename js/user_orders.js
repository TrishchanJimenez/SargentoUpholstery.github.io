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
// document.getElementById('attachmentForm').addEventListener('submit', function (event) {
//     event.preventDefault(); // Prevent default form submission
//     var formData = new FormData(); // Create a FormData object
//     var fileInput = document.getElementById('fileInput'); // Get the file input element
//     var files = fileInput.files; // Get the selected files
//     // Check if files are selected
//     if (files.length > 0) {
//         // Append the files to FormData
//         for (var i = 0; i < files.length; i++) {
//             var file = files[i];
//             formData.append('attachments[]', file, file.name);
//         }
//         // You can send the FormData to the server using AJAX or submit the form
//         // For demonstration, let's just log the FormData
//         console.log(formData);
//     } else {
//         alert('Please select a file.');
//     }
// });

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

const ToBeReviewedTab = document.querySelector('#tab7');
ToBeReviewedTab.addEventListener('click', (e) => {
    console.log('ToBeReviewedTab clicked');
    const reviewButton = e.target.closest('.review-button');
    if (reviewButton) {
        const orderId = reviewButton.closest('.order-container').dataset.id;
        reviewForm.classList.toggle('hidden');
    }
});

const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating');
let currentStarValue;
let tempStarValue;

const ratingStars = document.getElementById('ratingStars');
ratingStars.addEventListener('mouseover', (event) => {
    const target = event.target;
    if (target.classList.contains('star')) {
        tempStarValue = target.getAttribute('data-value');
        highlightStars(tempStarValue);
    }
});

ratingStars.addEventListener('mouseout', () => {    
    highlightStars(currentStarValue); 
});

ratingStars.addEventListener('click', (event) => {
    const target = event.target;
    if (target.classList.contains('star')) {
        currentStarValue = target.getAttribute('data-value');
        ratingInput.value = currentStarValue;
        highlightStars(currentStarValue);
    }
});

function highlightStars(value) {
    stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'));
        if (starValue <= value) {
            star.style.color = '#f39c12';
        } else {
            star.style.color = '#ddd';
        }
    });
}

let order_id;
const imagePreview = document.getElementById('imagesPreview');
function previewImages(event) {
    imagePreview.innerHTML = '';
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function (e) {
            const image = document.createElement('img');
            image.src = e.target.result;
            imagePreview.appendChild(image);
        };

        reader.readAsDataURL(file);
    }
}

const reviewForm = document.getElementById('reviewForm');
reviewForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const reviewData = new FormData(reviewForm);
    reviewData.append('order_id', orderToBeReviewedId);
    fetch('/api/submit_review.php', {
        method: 'POST',
        body: reviewData
    })
    .then(response => { 
        // console.log(response.text());
        return response.json()
    })  
    .then(data => {
        if (data.success) {
            alert('Review submitted successfully!');
            // window.location.href = '/user_orders.php';
        } else {
            alert('An error occurred while submitting the review.');
        }
    })
});

function closeReviewModal() {
    reviewForm.closest('.review-background').style.display = 'none';
}