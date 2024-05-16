    // Function to open the edit modal and populate form fields with existing data
    function editFurniture(category, color, imgPath, worksId) {
        // Open the edit modal (assuming you have a modal with ID 'editModal')
        openEditModal();
            console.log('Editing furniture with:', category, color, imgPath, worksId);
        
                // Populate form fields with existing furniture data
                document.getElementById('edit_category').value = category;
                document.getElementById('edit_color').value = color;

                // Display the existing furniture image in a preview (assuming you have an image container with ID 'editImagePreview')
                var imagePreview = '<img src="' + imgPath + '" alt="Furniture Image" style="max-width: 100px;">';
                document.getElementById('editImagePreview').innerHTML = imagePreview;

                // Add event listener to the submit button inside the edit modal
                document.getElementById('editSubmitButton').onclick = function() {
                    // Retrieve updated values from form fields
                    var updatedCategory = document.getElementById('edit_category').value;
                    var updatedColor = document.getElementById('edit_color').value;

                    // Perform validation or additional logic if needed

                    // Prepare data to send via AJAX (assuming you use AJAX for updating)
                    var formData = new FormData();
                    formData.append('category', updatedCategory);
                    formData.append('color', updatedColor);
                    formData.append('works_id', worksId); // Include the works_id for identifying the specific furniture

                    // Send AJAX request to update the furniture data
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_furniture.php', true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Handle successful response (e.g., close modal, refresh page)
                            closeModal(); // Close the edit modal
                            window.location.reload(); // Refresh the page to reflect changes
                        } else {
                            // Handle error or display error message
                            console.error('Error updating furniture: ' + xhr.responseText);
                            // Optionally display an error message to the user
                        }
                    };
                    xhr.onerror = function() {
                        console.error('Network error occurred');
                        // Display an error message to the user
                    };
                    xhr.send(formData); // Send the form data via AJAX
                };
            }


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





    