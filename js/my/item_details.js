// ---------- Modal for item details ---------- //

document.addEventListener('DOMContentLoaded', function() {
    const itemRows = document.querySelectorAll('.items__tr');
    const itemDetailsModal = document.getElementById('itemDetailsModal');
    const closeItemDetails = document.getElementById('closeItemDetails');
    const customsContent = document.getElementById('customsDetails');

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

                // Check for custom_id before displaying customization fields
                const customId = cells[6].innerText;
                if (customId) {
                    document.getElementById('modalDimensions').innerText = cells[7].innerText;
                    document.getElementById('modalMaterials').innerText = cells[8].innerText;
                    document.getElementById('modalFabric').innerText = cells[9].innerText;
                    document.getElementById('modalColor').innerText = cells[10].innerText;
                    
                    // Show the customization content
                    customsContent.style.display = 'flex';
                } else {
                    // Hide the customization content
                    customsContent.style.display = 'none';
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