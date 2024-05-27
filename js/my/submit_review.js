document.addEventListener('DOMContentLoaded', () => {
    const reviewBtn = document.querySelector('.button--review');
    const reviewForm = document.getElementById('reviewForm');
    const ratingStars = document.getElementById('ratingStars');
    const stars = document.querySelectorAll('.rating__star');
    const ratingInput = document.getElementById('rating');
    const imagePreview = document.getElementById('imagesPreview');
    const reviewImageInput = document.getElementById('images');
    let currentStarValue = 0;

    // Open review modal
    reviewBtn.addEventListener('click', () => {
        openReviewModal();
        resetStars();
    });

    // Star rating hover effect
    ratingStars.addEventListener('mouseover', (event) => {
        if (event.target.classList.contains('rating__star')) {
            const tempStarValue = event.target.getAttribute('data-value');
            highlightStars(tempStarValue);
        }
    });

    // Reset stars on mouse out
    ratingStars.addEventListener('mouseout', () => {
        highlightStars(currentStarValue);
    });

    // Set rating on click
    ratingStars.addEventListener('click', (event) => {
        if (event.target.classList.contains('rating__star')) {
            currentStarValue = event.target.getAttribute('data-value');
            ratingInput.value = currentStarValue;
            highlightStars(currentStarValue);
        }
    });

    // Highlight stars based on rating
    function highlightStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            star.style.color = starValue <= value ? '#f39c12' : '#ddd';
        });
    }

    // Reset stars to initial state
    function resetStars() {
        currentStarValue = 0;
        ratingInput.value = 0;
        highlightStars(0);
    }

    // Preview selected images
    window.previewImages = function(event) {
        imagePreview.innerHTML = '';
        const files = event.target.files;
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const image = document.createElement('img');
                image.src = e.target.result;
                image.classList.add('form__image-preview');
                imagePreview.appendChild(image);
            };
            reader.readAsDataURL(file);
        });
    };

    // Open the review modal
    window.openReviewModal = function() {
        document.querySelector('.modal--review').style.display = 'flex';
    };

    // Close the review modal
    window.closeReviewModal = function() {
        document.querySelector('.modal--review').style.display = 'none';
    };

    // Handle form submission
    reviewForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const reviewData = new FormData(reviewForm);
        fetch('/api/submit_review.php', {
            method: 'POST',
            body: reviewData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review submitted successfully!');
                closeReviewModal();
                window.location.href = '/testimonials.php';
            } else {
                alert('An error occurred while submitting the review. 1');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the review. 2');
        });
    });

    // Simulate click to upload image
    window.clickUploadImage = function() {
        reviewImageInput.click();
    };
});