// ---------- Form Validation ---------- //
document.addEventListener('DOMContentLoaded', function() {
    // Get a reference to the form element
    const form = document.querySelector('.quote-form');

    // Function to validate the form fields
    // function validateForm() {
    //     let isValid = true;

    //     // Validate full name
    //     const fullNameInput = form.querySelector('#name');
    //     if (fullNameInput.value.trim() === '') {
    //         isValid = false;
    //         fullNameInput.classList.add('invalid');
    //         alert("Name is invalid.");
    //     } else {
    //         fullNameInput.classList.remove('invalid');
    //     }

    //     // Validate address
    //     const addressSelect = form.querySelector('#user_address');
    //     if (addressSelect.value === '') {
    //         isValid = false;
    //         addressSelect.classList.add('invalid');
    //         alert("Address is invalid.");
    //     } else {
    //         addressSelect.classList.remove('invalid');
    //     }

    //     // Validate contact number
    //     const contactInput = form.querySelector('#contact_no');
    //     const contactPattern = /^[0-9]{11}$/;
    //     if (!contactPattern.test(contactInput.value.trim())) {
    //         isValid = false;
    //         contactInput.classList.add('invalid');
    //         alert("Contact number is invalid.");
    //     } else {
    //         contactInput.classList.remove('invalid');
    //     }

    //     // Validate furniture type
    //     const furnitureTypeInput = form.querySelector('#furniture_type');
    //     if (furnitureTypeInput.value.trim() === '') {
    //         isValid = false;
    //         furnitureTypeInput.classList.add('invalid');
    //         alert("Furniture type is invalid.");
    //     } else {
    //         furnitureTypeInput.classList.remove('invalid');
    //     }

    //     // Validate furniture description
    //     const furnitureDescriptionInput = form.querySelector('#description');
    //     if (furnitureDescriptionInput.value.trim() === '') {
    //         isValid = false;
    //         furnitureDescriptionInput.classList.add('invalid');
    //         alert("Description is invalid.");
    //     } else {
    //         furnitureDescriptionInput.classList.remove('invalid');
    //     }

    //     // Validate reference image (if provided)
    //     const referenceImageInput = form.querySelector('#ref_img');
    //     if (referenceImageInput.files.length > 0) {
    //         const maxFileSize = 5 * 1024 * 1024; // 5MB (in bytes)

    //         const file = referenceImageInput.files[0];
    //         if (file.size > maxFileSize) {
    //             isValid = false;
    //             referenceImageInput.classList.add('invalid');
    //             alert("Uploaded image is invalid.");
    //         } else {
    //             referenceImageInput.classList.remove('invalid');
    //         }
    //     }

    //     // Validate quantity
    //     const quantityInput = form.querySelector('#quantity');
    //     if (quantityInput.value.trim() === '' || parseInt(quantityInput.value.trim()) < 1) {
    //         isValid = false;
    //         quantityInput.classList.add('invalid');
    //         alert("Quantity is invalid.");
    //     } else {
    //         quantityInput.classList.remove('invalid');
    //     }

    //     return isValid;
    // }

    // // Function to handle form submission
    // function submitForm(event) {
    //     event.preventDefault(); // Prevent the default form submission behavior
        
    //     if (validateForm()) {
    //         // Form is valid, submit it
    //         form.submit();
    //     } else {
    //         // Form is invalid, display an error message or handle it as needed
    //         alert('Please fill out all required fields correctly.');
    //     }
    // }

    // // Add an event listener for form submission
    // form.addEventListener('submit', submitForm);
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

// ---------- HELPER FUNCTIONS ---------- //
const serviceSelect = document.getElementById('service_type');
const furnitureContainer = document.querySelector('.quote-form__furniture-container');
const repairContainer = document.querySelector('.quote-form__furniture-repair-container');
const addItemButton = document.getElementById('add-item');
const addRepairItemButton = document.getElementById('add-item-repair');

let changed = false;

function disableInputs(container) {
    const disabledInputs = container.querySelectorAll(' .required');
    disabledInputs.forEach(input => {
        input.setAttribute('disabled', true);
    });
}

function enableInputs(container) {
    const enabledInputs = container.querySelectorAll(' .required');
    enabledInputs.forEach(input => {
        input.removeAttribute('disabled');
    });
}

function displayAppropriateInput(event) {
    if(event.target.value === 'mto') {
        furnitureContainer.style.display = 'block';
        repairContainer.style.display = 'none';
        disableInputs(repairContainer);
        enableInputs(furnitureContainer);
        if(!changed) {
            console.log(changed);
            addItem();
            changed = true;
        }
    } else {
        furnitureContainer.style.display = 'none';
        repairContainer.style.display = 'block';
        disableInputs(furnitureContainer);
        enableInputs(repairContainer);
    }
}

function removeItem(item) {
    if (furnitureContainer.childElementCount > 2) {
        item.remove();
    }
}

function removeRepairItem(item) {
    console.log(repairContainer.childElementCount);
    if (repairContainer.childElementCount > 2) {
        item.remove();
    }
}

function addItem() {
    addItemButton.insertAdjacentHTML('beforebegin', furnitureInput);
}

function addRepairItem() {
    addRepairItemButton.insertAdjacentHTML('beforebegin', itemRepairInput);
}

const furnitureInput = `
<div class="quote-form__section quote-form__furniture-item">
    <h2 class="quote-form__header">Furniture Detail<i class="fa fa-close remove-item" onclick="removeItem(this.closest('.quote-form__furniture-item'))"></i></h2>
    <div class="quote-form_furniture-item_main-detail">
        <div class="quote-form__input-container">
            <label class="quote-form__label" for="furniture_type">Furniture Type *</label> 
            <input class="quote-form__input quote-form__input--text required" type="text" id="furniture_type" name="furniture_type[]" placeholder="E.g. sofa, dining seats, bed" required cols="25">
        </div>

        <div class="quote-form__input-container">
            <label class="quote-form__label" for="description">Furniture Description *</label> 
            <textarea class="quote-form__input quote-form__input--textarea required" id="description" name="description[]" placeholder="Please describe the furniture in detail." required></textarea>
        </div>

        <div class="quote-form__input-container quote-form__input-container--file">
            <label class="quote-form__label" for="ref_img">Reference Image</label> 
            <input class="quote-form__input quote-form__input--file" type="file" id="ref_img" name="ref_img[]" accept="images/*">
        </div>

        <div class="quote-form__input-container">
            <label class="quote-form__label" for="quantity">Quantity *</label> 
            <input class="quote-form__input quote-form__input--number required" type="number" id="quantity" name="quantity[]" value="1" min="1" max="50" required>
        </div>
    </div>
    <div class="quote-form_furniture-item_sub-detail">
        <div class="quote-form__input-container">
            <label class="quote-form__label" for="dimensions">Specify Dimensions (in meters)</label>
            <input class="quote-form__input quote-form__input--text" type="text" id="dimensions" name="dimensions[]" placeholder="Length x Width x Height">
        </div>

        <div class="quote-form__input-container">
            <label class="quote-form__label" for="materials">Specify Materials</label>
            <input class="quote-form__input quote-form__input--text" type="text" id="materials" name="materials[]" placeholder="E.g. wood, plastic, metal">
        </div>

        <div class="quote-form__input-container">
            <label class="quote-form__label" for="fabric">Specify Fabric</label>
            <input class="quote-form__input quote-form__input--text" type="text" id="fabric" name="fabric[]" placeholder="E.g cotton, linen, leather">
        </div>

        <div class="quote-form__input-container">
            <label class="quote-form__label" for="color">Specify Color</label>
            <input class="quote-form__input quote-form__input--text" type="text" id="color" name="color[]" placeholder="E.g black, blue, red">
        </div>
    </div>
</div>
`;

const itemRepairInput = `
    <div class="quote-form__section quote-form__furniture-item-repair">
        <h2 class="quote-form__header">Repair Detail<i class="fa fa-close remove-item" onclick="removeRepairItem(this.closest('.quote-form__furniture-item-repair'))"></i></h2>
        <div class="quote-form_furniture-item_main-detail">
            <div class="quote-form__input-container">
                <label class="quote-form__label" for="furniture_type">Furniture Type *</label> 
                <input class="quote-form__input quote-form__input--text required" type="text" id="furniture_type" name="furniture_type[]" placeholder="E.g. sofa, dining seats, bed" required cols="25">
            </div>

            <div class="quote-form__input-container">
                <label class="quote-form__label" for="quantity">Quantity *</label> 
                <input class="quote-form__input quote-form__input--number required" type="number" id="quantity" name="quantity[]" value="1" min="1" max="50" required>
            </div>

            <div class="quote-form__input-container">
                <label class="quote-form__label" for="description">Furniture Description *</label> 
                <textarea class="quote-form__input quote-form__input--textarea required" id="description" name="description[]" placeholder="Please describe the damage to the furniture in detail." required></textarea>
            </div>

            <div class="quote-form__input-container quote-form__input-container--file">
                <label class="quote-form__label" for="ref_img">Reference Image</label> 
                <input class="quote-form__input quote-form__input--file" type="file" id="ref_img" name="ref_img[]" accept="images/*">
            </div>
        </div>
    </div>
`;