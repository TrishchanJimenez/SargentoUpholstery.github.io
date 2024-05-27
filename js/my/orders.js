// Select the confirmation button
const confirmDeliveryBtn = document.querySelector('.order-actions__confirm-delivery-button');

// Add click event listener to the confirmation button
confirmDeliveryBtn.addEventListener('click', () => {
    // Get the order ID from the HTML attribute or from a hidden input field
    const orderId = /* Code to get the order ID */;

    // Make an AJAX request to the PHP script
    fetch('../my/confirm_delivery.php', {
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