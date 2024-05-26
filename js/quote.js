// ---------- Form Validation ---------- //
document.addEventListener('DOMContentLoaded', function() {
    // Get a reference to the form element
    const form = document.querySelector('.quote-form');

    // Function to validate the form fields
    function validateForm() {
        let isValid = true;

        // Validate full name
        const fullNameInput = form.querySelector('#name');
        if (fullNameInput.value.trim() === '') {
            isValid = false;
            fullNameInput.classList.add('invalid');
            alert("Name is invalid.");
        } else {
            fullNameInput.classList.remove('invalid');
        }

        // Validate address
        const addressSelect = form.querySelector('#user_address');
        if (addressSelect.value === '') {
            isValid = false;
            addressSelect.classList.add('invalid');
            alert("Address is invalid.");
        } else {
            addressSelect.classList.remove('invalid');
        }

        // Validate contact number
        const contactInput = form.querySelector('#contact_no');
        const contactPattern = /^[0-9]{11}$/;
        if (!contactPattern.test(contactInput.value.trim())) {
            isValid = false;
            contactInput.classList.add('invalid');
            alert("Contact number is invalid.");
        } else {
            contactInput.classList.remove('invalid');
        }

        // Validate furniture type
        const furnitureTypeInput = form.querySelector('#furniture_type');
        if (furnitureTypeInput.value.trim() === '') {
            isValid = false;
            furnitureTypeInput.classList.add('invalid');
            alert("Furniture type is invalid.");
        } else {
            furnitureTypeInput.classList.remove('invalid');
        }

        // Validate furniture description
        const furnitureDescriptionInput = form.querySelector('#description');
        if (furnitureDescriptionInput.value.trim() === '') {
            isValid = false;
            furnitureDescriptionInput.classList.add('invalid');
            alert("Description is invalid.");
        } else {
            furnitureDescriptionInput.classList.remove('invalid');
        }

        // Validate reference image (if provided)
        const referenceImageInput = form.querySelector('#ref_img');
        if (referenceImageInput.files.length > 0) {
            const maxFileSize = 5 * 1024 * 1024; // 5MB (in bytes)

            const file = referenceImageInput.files[0];
            if (file.size > maxFileSize) {
                isValid = false;
                referenceImageInput.classList.add('invalid');
                alert("Uploaded image is invalid.");
            } else {
                referenceImageInput.classList.remove('invalid');
            }
        }

        // Validate quantity
        const quantityInput = form.querySelector('#quantity');
        if (quantityInput.value.trim() === '' || parseInt(quantityInput.value.trim()) < 1) {
            isValid = false;
            quantityInput.classList.add('invalid');
            alert("Quantity is invalid.");
        } else {
            quantityInput.classList.remove('invalid');
        }

        return isValid;
    }

    // Function to handle form submission
    function submitForm(event) {
        event.preventDefault(); // Prevent the default form submission behavior
        
        if (validateForm()) {
            // Form is valid, submit it
            form.submit();
        } else {
            // Form is invalid, display an error message or handle it as needed
            alert('Please fill out all required fields correctly.');
        }
    }

    // Add an event listener for form submission
    form.addEventListener('submit', submitForm);
});

// ---------- Toggle Customization Window ---------- //
document.addEventListener("DOMContentLoaded", function() {
    // Get references to the elements you want to interact with
    var enableCustomizationCheckbox = document.getElementById("enable_customization");
    var customizationSection = document.getElementById("customization");

    // Function to show or hide the customization section based on checkbox state
    function toggleCustomizationSection() {
        if (enableCustomizationCheckbox.checked) {
            customizationSection.style.display = "block";
        } else {
            customizationSection.style.display = "none";
        }
    }

    // Initially hide the customization section
    toggleCustomizationSection();

    // Add event listener to the checkbox for dynamic interaction
    enableCustomizationCheckbox.addEventListener("change", function() {
        toggleCustomizationSection();
    });
});