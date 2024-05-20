// Function to handle file selection and display image previews
function handleFileSelect(event) {
    const fileList = event.target.files;
    const imagePreviews = document.getElementById('imagePreviews');

    // Clear existing previews
    imagePreviews.innerHTML = '';

    // Loop through selected files
    for (let i = 0; i < fileList.length; i++) {
        const file = fileList[i];

        // Ensure file is an image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            // Create image element for preview
            const imgElement = document.createElement('img');
            imgElement.classList.add('preview-image');
            imagePreviews.appendChild(imgElement);

            // Read file as data URL and set image source
            reader.onload = function (e) {
                imgElement.src = e.target.result;
            };

            // Read file as data URL
            reader.readAsDataURL(file);
        }
    }
}

// Attach event listener to file input change
document.getElementById('fileInput').addEventListener('change', handleFileSelect);

// Function to open modal dialog
function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

// Function to close modal dialog
function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

// Function to open modal dialog centered on the screen
function openModal() {
    const modal = document.getElementById('myModal');
    modal.style.display = 'block';

    // Center the modal vertically and horizontally
    modal.querySelector('.modal-content').style.top = '50%';
    modal.querySelector('.modal-content').style.left = '50%';
    modal.querySelector('.modal-content').style.transform = 'translate(-50%, -50%)';
}

// Function to close modal dialog
function closeModal() {
    const modal = document.getElementById('myModal');
    modal.style.display = 'none';
}


function resetForm() {
    const form = document.getElementById('addForm');
    if (form) {
        form.reset(); // Reset the form

        // Clear file input previews if applicable
        const imagePreviews = document.getElementById('imagePreviews');
        if (imagePreviews) {
            imagePreviews.innerHTML = ''; // Clear image previews
        }
    }
}

function cancelButton(){
    closeModal();
    resetForm();
}

//For auto reloads
// Function to reload the page when a pagination link is clicked
function handlePageChange() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior

            // Get the href attribute of the clicked link
            const href = this.getAttribute('href');

            // Perform a page reload with the new URL
            window.location.href = href;
        });
    });
}

// Call the function to handle page changes
handlePageChange();
// Function to open the edit modal
function openEditModal() {
    document.getElementById('editModal').style.display = 'block';
}

// Function to close the edit modal
function closeeditModal() {
    document.getElementById('editModal').style.display = 'none';
}


function reseteditForm() {
    const form = document.getElementById('editForm');
    if (form) {
        form.reset(); // Reset the form

        // Clear file input previews if applicable
        const imagePreviews = document.getElementById('imagePreviews');
        if (imagePreviews) {
            imagePreviews.innerHTML = ''; // Clear image previews
        }
    }
}

function editcancelbutton(){
    closeeditModal();
    reseteditForm();
}

    // JavaScript function to set the editFurnitureId value
function setEditFurnitureId(furnitureId) {
    // Find the hidden input element by its ID
    var editFurnitureIdInput = document.getElementById('editFurnitureId');

    // Update the value of the hidden input field
    if (editFurnitureIdInput) {
        editFurnitureIdInput.value = furnitureId;
    }
}



function editFurniture(category, color, imgPath, worksId) {
    // Assuming you have a modal for editing furniture details
    // Set values in the modal fields based on the passed parameters
    document.getElementById('editCategory').value = category;
    document.getElementById('editColor').value = color;
    document.getElementById('currentImage').src = imgPath;
    document.getElementById('editFurnitureId').value = worksId;

    // Display the edit modal (assuming modal is displayed via CSS)
    document.getElementById('editModal').style.display = 'block';
}

function editcancelbutton() {
    // Hide the edit modal when cancel button is clicked
    document.getElementById('editModal').style.display = 'none';
}


function deleteFurniture(worksId) {
    // Confirm deletion with the user (optional)
    if (!confirm("Are you sure you want to delete this furniture?")) {
        return; // User cancelled deletion
    }
    
    // AJAX request to delete furniture
    fetch('../admin/delete_furniture.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `works_id=${worksId}`
    })
    .then(response => {
        if (response.ok) {
            // Furniture deleted successfully
            console.log('Furniture deleted successfully');
            // Perform additional actions if needed (e.g., update UI)
        } else {
            // Error handling
            console.error('Failed to delete furniture');
            // Display error message or handle accordingly
        }
    })
    .catch(error => {
        console.error('Error deleting furniture:', error);
        // Handle fetch error (e.g., network issue)
    });
}





    
