function toggleInputs() {
    var orderType = document.getElementById("orderType").value;
    var repairFieldset = document.querySelector(".quotation-form__fieldset--repair");
    var mtoFieldset = document.querySelector(".quotation-form__fieldset--mto");
    
    if (orderType === "repair") {
        repairFieldset.style.display = "block";
        mtoFieldset.style.display = "none";
    } else if (orderType === "mto") {
        repairFieldset.style.display = "none";
        mtoFieldset.style.display = "block";
    }
}

// Initial call to set initial state
toggleInputs();
