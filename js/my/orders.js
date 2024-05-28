// Select the confirmation button
const confirmBtn = document.querySelector('.form__submit--confirm');

// Add click event listener to the confirmation button
confirmBtn.addEventListener('click', () => {
    // Make an AJAX request to the PHP script
    fetch('../my/confirm_arrival.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ order_id: orderId })
    })
    .then(response => response.json())
    .then(data => {
        // Check if the request was successful
        if (data.success) {
            // Display a success message to the user
            alert('Order delivery confirmed successfully!');
            // You can also redirect the user to another page or perform any other action
            location.reload();
        } else {
            // Display an error message to the user
            alert('Failed to confirm order delivery. Please try again later.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Display an error message to the user
        alert('An unexpected error occurred. Please try again later.');
    });
});