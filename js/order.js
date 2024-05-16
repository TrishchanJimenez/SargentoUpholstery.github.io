function copyBillingAddress() {
    const billingAddress = document.getElementById('billing_address').value;
    const shippingAddressField = document.getElementById('shipping_address');
    const sameAsBilling = document.getElementById('same_as_billing').checked;

    if (sameAsBilling) {
        shippingAddressField.value = billingAddress;
        shippingAddressField.setAttribute('readonly', 'readonly');
    } else {
        shippingAddressField.value = '';
        shippingAddressField.removeAttribute('readonly');
    }
}

function updateShippingAddressOnBillingChange() {
    const billingAddressInput = document.getElementById('billing_address');
    const sameAsBillingCheckbox = document.getElementById('same_as_billing');

    billingAddressInput.addEventListener('input', function () {
        if (sameAsBillingCheckbox.checked) {
            const billingAddress = billingAddressInput.value;
            const shippingAddressField = document.getElementById('shipping_address');
            shippingAddressField.value = billingAddress;
        }
    });
}

const fieldsets = document.querySelectorAll('.quotation-form__fieldset');
const prevButton = document.querySelector('.quotation-form__prev-button');
const nextButton = document.querySelector('.quotation-form__next-button');
const submitButton = document.querySelector('.quotation-form__submit-button');

let currentFieldsetIndex = 0;

function showFieldset(index) {
    fieldsets.forEach((fieldset, idx) => {
        if (idx === index) {
            fieldset.style.display = 'flex';
        } else {
            fieldset.style.display = 'none';
        }
    });

    if (index === 0) {
        prevButton.style.display = 'none';
        nextButton.style.display = 'inline-block';
        submitButton.style.display = 'none';
    } else if (index === fieldsets.length - 1) {
        prevButton.style.display = 'inline-block';
        nextButton.style.display = 'none';
        submitButton.style.display = 'inline-block';
    } else {
        prevButton.style.display = 'inline-block';
        nextButton.style.display = 'inline-block';
        submitButton.style.display = 'none';
    }
}

function nextFieldset() {
    if (currentFieldsetIndex < fieldsets.length - 1) {
        currentFieldsetIndex++;
        showFieldset(currentFieldsetIndex);
    }
}

function prevFieldset() {
    if (currentFieldsetIndex > 0) {
        currentFieldsetIndex--;
        showFieldset(currentFieldsetIndex);
    }
}

showFieldset(currentFieldsetIndex);

function toggleInputs() {
    var order_type = document.getElementById("order_type").value;
    var repairInputContainerField = document.querySelector(".quotation-form__input-container-group--repair");
    var mtoInputContainerField = document.querySelector(".quotation-form__input-container-group--mto");

    if (order_type === "repair") {
        repairInputContainerField.style.display = "flex";
        mtoInputContainerField.style.display = "none";
        clearMTOFields();
    } else if (order_type === "mto") {
        repairInputContainerField.style.display = "none";
        mtoInputContainerField.style.display = "flex";
        clearRepairFields();
    }
}

function clearRepairFields() {
    // document.getElementById("thirdPartyPickup").checked = false;
    // document.getElementById("selfPickup").checked = false;
    document.getElementById("pickup_address").value = "";
    document.getElementById("setPickupAddress").checked = false;
    document.getElementById("repairPicture").value = "";

    // document.getElementById("thirdPartyPickup").required = false;
    // document.getElementById("selfPickup").required = false;
    document.getElementById("pickup_address").required = false;
    document.getElementById("setPickupAddress").required = false;
    document.getElementById("repairPicture").required = false;

    document.getElementById("width").required = true;
    document.getElementById("height").required = true;
    document.getElementById("depth").required = true;
    document.getElementById("material").required = true;
}

function clearMTOFields() {
    document.getElementById("width").value = "";
    document.getElementById("height").value = "";
    document.getElementById("depth").value = "";
    document.getElementById("material").value = "";

    document.getElementById("width").required = false;
    document.getElementById("height").required = false;
    document.getElementById("depth").required = false;
    document.getElementById("material").required = false;

    // document.getElementById("thirdPartyPickup").required = true;
    // document.getElementById("selfPickup").required = true;
    document.getElementById("pickup_address").required = true;
    document.getElementById("setPickupAddress").required = true;
    document.getElementById("repairPicture").required = true;
}

// Trigger toggleInputs() initially to ensure correct display
toggleInputs();
updateShippingAddressOnBillingChange();