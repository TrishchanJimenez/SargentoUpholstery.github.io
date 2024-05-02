function toggleInputs() {
    var orderCategory = document.getElementById("orderCategory").value;
    var repairFieldset = document.querySelector('.quotation-form__fieldset--repair');
    var customizedFieldset = document.querySelector('.quotation-form__fieldset--customized');
    if (orderCategory === "repair") {
        repairFieldset.style.display = "block";
        customizedFieldset.style.display = "none";
    } else if (orderCategory === "customized") {
        customizedFieldset.style.display = "block";
        repairFieldset.style.display = "none";
    }
}