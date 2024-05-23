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
    } else if (order_type === "mto") {
        repairInputContainerField.style.display = "none";
        clearRepairFields();
    }
}

function clearRepairFields() {
    // document.getElementById("thirdPartyPickup").checked = false;
    // document.getElementById("selfPickup").checked = false;
    document.getElementById("pickup_address").value = "";

    // document.getElementById("thirdPartyPickup").required = false;
    // document.getElementById("selfPickup").required = false;
    document.getElementById("pickup_address").required = false;
}

// Trigger toggleInputs() initially to ensure correct display
toggleInputs();