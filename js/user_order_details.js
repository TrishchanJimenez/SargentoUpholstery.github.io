// file upload
function handleFileUpload(orderId) {
    var fileInput = document.getElementById('fileUpload' + orderId);
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file', file);
    formData.append('order_id', orderId);

    fetch('/api/upload_proof_of_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // or response.json() if your server returns JSON
        } else {
            throw new Error('An error occurred while uploading the file.');
        }
    })
    .then(data => {
        alert('File uploaded successfully!');
    })
    .catch(error => {
        alert(error.message);
    });
}

const reviewBtn = document.querySelector('button.review-button');
const reviewForm = document.getElementById('reviewForm');
reviewBtn.addEventListener('click', () => {
    reviewForm.closest('.review-background').style.display = 'block';
    currentStarValue = 0;
    tempStarValue = 0;
    ratingInput.value = 0;
    highlightStars(0);
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

reviewForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const reviewData = new FormData(reviewForm);
    fetch('/api/submit_review.php', {
        method: 'POST',
        body: reviewData
    })
    .then(response => { 
        // console.log(response.text());
        Location('/testimonials.php');
        closeReviewModal();
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

const reviewImageInput = document.getElementById('reviewImages');

function clickUploadImage() {
    reviewImageInput.click(); 
}