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
const addFurnitureModal = document.getElementById('myModal');
function openModal() {
    addFurnitureModal.style.display = 'block';
}

// Function to close modal dialog
function closeModal() {
    addFurnitureModal.style.display = 'none';
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
function closeEditModal() {
    editModal.style.display = 'none';
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

const editModal = document.getElementById('editModal');
const categoryEditor = document.getElementById('editCategory');
const colorEditor = document.getElementById('editColor');
const imageEditor = document.getElementById('currentImage');
const editForm = editModal.querySelector('form');

function editFurniture(worksId) {
    // Display the edit modal (assuming modal is displayed via CSS)
    editModal.style.display = 'block';
    // Assuming you have a modal for editing furniture details
    // Set values in the modal fields based on the passed parameters
    fetch(`get_furniture.php?works_id=${worksId}`, {
        method: 'GET',
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Failed to fetch furniture data');
        }
    })
    .then(data => {
    // Update the modal fields with the fetched data
    categoryEditor.value = data.category;
    colorEditor.value = data.color.charAt(0).toUpperCase() + data.color.slice(1).toLowerCase();
    imageEditor.src = data.image;
    });
}

editForm.addEventListener('submit', (e) => {
    e.preventDefault();
});

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
    fetch('delete_furniture.php', {
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