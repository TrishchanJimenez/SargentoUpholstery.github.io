// ---------- Modal for quote actions ---------- //

document.addEventListener('DOMContentLoaded', function () {
    const acceptModal = document.getElementById('acceptModal');
    const cancelModal = document.getElementById('cancelModal');
    const confirmAcceptAction = document.getElementById('confirmAcceptAction');
    const cancelAcceptAction = document.getElementById('cancelAcceptAction');
    const confirmCancelAction = document.getElementById('confirmCancelAction');
    const cancelCancelAction = document.getElementById('cancelCancelAction');

    function openModal(action) {
        if (action === 'accept') {
            acceptModal.style.display = 'block';
        } else if (action === 'cancel') {
            cancelModal.style.display = 'block';
        }
    }

    function closeModal() {
        acceptModal.style.display = 'none';
        cancelModal.style.display = 'none';
    }

    cancelAcceptAction.addEventListener('click', closeModal);
    cancelCancelAction.addEventListener('click', closeModal);

    confirmAcceptAction.addEventListener('click', function () {
        updateQuoteStatus('accepted');
        closeModal();
    });

    confirmCancelAction.addEventListener('click', function () {
        updateQuoteStatus('cancelled');
        closeModal();
    });

    // Expose openModal to global scope
    window.openModal = openModal;

    function updateQuoteStatus(status) {
        fetch("../api/update_quote_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `quote_id=${quoteId}&status=${status}`
        })
        .then(response => {
            if (response.ok) {
                alert("Quote status updated successfully.");
                location.reload();
            } else {
                alert("Failed to update quote status.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Failed to update quote status.");
        });
    }
});

// ---------- Modal for item details ---------- //

document.addEventListener('DOMContentLoaded', function() {
    const itemRows = document.querySelectorAll('.quote-items__tr');
    const itemDetailsModal = document.getElementById('itemDetailsModal');
    const closeItemDetails = document.getElementById('closeItemDetails');

    itemRows.forEach(row => {
        row.addEventListener('click', function() {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                document.getElementById('modalFurnitureType').innerText = cells[1].innerText;
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
