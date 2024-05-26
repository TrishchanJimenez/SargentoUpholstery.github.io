// ---------- Form Validation ---------- //
document.addEventListener('DOMContentLoaded', function() {
    // Get a reference to the form element
    const form = document.querySelector('.quote-form');

    // Function to validate the form fields
    function validateForm() {
        let isValid = true;

        // Validate full name
        const fullNameInput = form.querySelector('#customer-name');
        if (fullNameInput.value.trim() === '') {
            isValid = false;
            fullNameInput.classList.add('invalid');
        } else {
            fullNameInput.classList.remove('invalid');
        }

        // Validate address
        const addressSelect = form.querySelector('#customer-address');
        if (addressSelect.value === '') {
            isValid = false;
            addressSelect.classList.add('invalid');
        } else {
            addressSelect.classList.remove('invalid');
        }

        // Validate contact number
        const contactInput = form.querySelector('#customer-contact');
        const contactPattern = /^[0-9]{10}$/;
        if (!contactPattern.test(contactInput.value.trim())) {
            isValid = false;
            contactInput.classList.add('invalid');
        } else {
            contactInput.classList.remove('invalid');
        }

        // Validate furniture type
        const furnitureTypeInput = form.querySelector('#furniture-type');
        if (furnitureTypeInput.value.trim() === '') {
            isValid = false;
            furnitureTypeInput.classList.add('invalid');
        } else {
            furnitureTypeInput.classList.remove('invalid');
        }

        // Validate furniture description
        const furnitureDescriptionInput = form.querySelector('#furniture-description');
        if (furnitureDescriptionInput.value.trim() === '') {
            isValid = false;
            furnitureDescriptionInput.classList.add('invalid');
        } else {
            furnitureDescriptionInput.classList.remove('invalid');
        }

        // Validate reference image (if provided)
        const referenceImageInput = form.querySelector('#furniture-reference-image');
        if (referenceImageInput.files.length > 0) {
            const maxFileSize = 5 * 1024 * 1024; // 5MB (in bytes)

            const file = referenceImageInput.files[0];
            if (file.size > maxFileSize) {
                isValid = false;
                referenceImageInput.classList.add('invalid');
            } else {
                referenceImageInput.classList.remove('invalid');
            }
        }

        // Validate quantity
        const quantityInput = form.querySelector('#furniture-quantity');
        if (quantityInput.value.trim() === '' || parseInt(quantityInput.value.trim()) < 1) {
            isValid = false;
            quantityInput.classList.add('invalid');
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
    var enableCustomizationCheckbox = document.getElementById("furniture-enable-customization");
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

// ---------- Toggle Customization Options ---------- //
document.addEventListener("DOMContentLoaded", function() {
    // Get all customization checkboxes and inputs
    const customizationCheckboxes = document.querySelectorAll('.quote-form__input--checkbox.quote-form__input--customization');
    
    customizationCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const inputId = checkbox.id.replace('enable-', '');
            const correspondingInput = document.getElementById(inputId);

            if (checkbox.checked) {
                correspondingInput.disabled = false;
                correspondingInput.style.cursor = 'text'; // Change cursor to text
            } else {
                correspondingInput.disabled = true;
                correspondingInput.style.cursor = 'not-allowed'; // Change cursor to not-allowed
            }
        });
    });
});