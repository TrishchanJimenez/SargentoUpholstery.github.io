document.addEventListener('DOMContentLoaded', () => {
    const reviewsContainer = document.querySelector('.reviews-container');

    // Function to submit form data via AJAX
    async function submitFormData(url, formData) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Failed to submit data');
            }

            return response.json(); // Parse response as JSON
        } catch (error) {
            console.error('Error:', error.message);
            throw error; // Re-throw the error for higher-level handling
        }
    }

    // Function to handle review form submission
    async function handleReviewSubmission(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await submitFormData('submit_review.php', formData);
            console.log('Review submitted successfully:', response);

            // Clear the review form inputs
            form.reset();

            // Reload reviews after successful submission
            loadReviews();
        } catch (error) {
            console.error('Failed to submit review:', error.message);
        }
    }

    // Function to handle reply form submission
    async function handleReplySubmission(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await submitFormData('submit_reply.php', formData);
            console.log('Reply submitted successfully:', response);

            // Reload reviews after successful reply submission
            loadReviews();
        } catch (error) {
            console.error('Failed to submit reply:', error.message);
        }
    }

    
    // Event listener for review form submission
    const reviewForm = document.getElementById('review-form');
    reviewForm.addEventListener('submit', handleReviewSubmission);

    // Event listener for reply form submission (using event delegation)
    document.addEventListener('submit', (e) => {
        if (e.target.classList.contains('reply-form')) {
            handleReplySubmission(e);
        }
    });

    // Function to load reviews from the server and update the UI
    function loadReviews() {
        fetch('get_reviews.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load reviews');
                }
                return response.text(); // Get response body as text
            })
            .then(data => {
                reviewsContainer.innerHTML = data; // Update the reviews container
            })
            .catch(error => {
                console.error('Error:', error.message);
            });
    }

    // Load reviews initially when the page loads
    loadReviews();

    // Periodically load reviews at an interval (e.g., every 30 seconds)
    const refreshInterval = 30000; // 30 seconds in milliseconds
    setInterval(loadReviews, refreshInterval);
});
