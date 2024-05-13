function toggleInputs() {
    var order_type = document.getElementById("order_type").value;
    var repairFieldset = document.querySelector(".quotation-form__fieldset--repair");
    var mtoFieldset = document.querySelector(".quotation-form__fieldset--mto");

    if (order_type === "repair") {
        repairFieldset.style.display = "block";
        mtoFieldset.style.display = "none";
        clearMTOFields();
    } else if (order_type === "mto") {
        repairFieldset.style.display = "none";
        mtoFieldset.style.display = "block";
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