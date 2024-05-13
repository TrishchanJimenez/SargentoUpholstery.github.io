function toggleInputs() {
    var order_type = document.getElementById("order_type").value;
    var repairFieldset = document.querySelector(".quotation-form__fieldset--repair");
    var mtoFieldset = document.querySelector(".quotation-form__fieldset--mto");

    if (order_type === "repair") {
        repairFieldset.style.display = "block";
        mtoFieldset.style.display = "none";
        toggleRequired(repairFieldset, true);
        toggleRequired(mtoFieldset, false);
        clearMTOFields(); // Optionally clear MTO fields
    } else if (order_type === "mto") {
        repairFieldset.style.display = "none";
        mtoFieldset.style.display = "block";
        toggleRequired(repairFieldset, false);
        toggleRequired(mtoFieldset, true);
        clearRepairFields(); // Optionally clear Repair fields
    }
}

function toggleRequired(fieldset, required) {
    var inputs = fieldset.querySelectorAll('.quotation-form__input, .quotation-form__select, .quotation-form__textarea');
    inputs.forEach(function(input) {
        input.required = required;
    });
}

function clearRepairFields() {
    document.getElementById("pickup_method").value = ""; // Clear pickup method dropdown
    document.getElementById("pickup_address").value = ""; // Clear pickup address
    document.getElementById("repairPicture").value = ""; // Clear repair picture input
}

function clearMTOFields() {
    document.getElementById("width").value = ""; // Clear width input
    document.getElementById("height").value = ""; // Clear height input
    document.getElementById("depth").value = ""; // Clear depth input
    document.getElementById("material").value = ""; // Clear material input
}

// Trigger toggleInputs() initially to ensure correct display
toggleInputs();