/* // Select the confirmation button
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
}); */

// ---------- Modal for order actions ---------- //

document.addEventListener('DOMContentLoaded', function () {
    const soaModal = document.getElementById('soaModal');
    const closeSOA = document.getElementById('closeSOA');
    const upodModal = document.getElementById('upodModal');
    const closeUPOD = document.getElementById('closeUPOD');

    function openModal(action) {
        switch(action) {
            case 'soa':
                soaModal.style.display = 'flex';
                break;
            case 'upod':
                upodModal.style.display = 'flex';
                break;
            default:
                break;
        }
    }

    closeSOA.addEventListener('click', function() {
        soaModal.style.display = 'none';
    });

    closeUPOD.addEventListener('click', function() {
        upodModal.style.display = 'none';
    });

    // Expose openModal to global scope
    window.openModal = openModal;
});



// ---------- Modal for item details ---------- //

document.addEventListener('DOMContentLoaded', function() {
    const itemRows = document.querySelectorAll('.items__tr');
    const itemDetailsModal = document.getElementById('itemDetailsModal');
    const closeItemDetails = document.getElementById('closeItemDetails');

    itemRows.forEach(row => {
        row.addEventListener('click', function() {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                document.getElementById('modalFurniture').innerText = cells[1].innerText;
                document.getElementById('modalDescription').innerText = cells[2].innerText;
                document.getElementById('modalQuantity').innerText = cells[3].innerText;
                document.getElementById('modalPrice').innerText = cells[4].innerText;

                const refImageCell = cells[5].querySelector('img');
                const refImageContainer = document.getElementById('modalRefImage');
                refImageContainer.innerHTML = ''; // Clear previous content
                if (refImageCell) {
                    const refImage = document.createElement('img');
                    refImage.src = refImageCell.src;
                    refImage.alt = 'Item image';
                    refImage.width = 200;
                    refImageContainer.appendChild(refImage);
                } else {
                    refImageContainer.innerText = 'No image uploaded.';
                }

                itemDetailsModal.style.display = 'flex';
            }
        });
    });

    closeItemDetails.addEventListener('click', function() {
        itemDetailsModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == itemDetailsModal) {
            itemDetailsModal.style.display = 'none';
        }
    });
});
