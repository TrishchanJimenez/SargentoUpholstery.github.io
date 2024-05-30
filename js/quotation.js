const actionButtons = document.querySelector(".order-action .action-buttons") ?? null;
if (actionButtons !== null) {
    const quoteRejectForm = document.querySelector("#quote-reject-form");
    const formButtons = quoteRejectForm.querySelector(".on-click");

    const cancelButton = formButtons.querySelector('input[type="button"]');
    const rejectButton = document.querySelector(".reject-quote");
    const rejectReasonInput = quoteRejectForm.querySelector(".rejection-input");

    rejectButton.addEventListener("click", (e) => {
        actionButtons.style.display = "none";
        formButtons.style.display = "flex";
        quoteRejectForm.style.display = "block";
    });

    cancelButton.addEventListener("click", (e) => {
        formButtons.style.display = "none";
        actionButtons.style.display = "flex";
        quoteRejectForm.style.display = "none";
    });
}

function closeModal() {
    document.querySelector(".quote-modal-background").style.display = "none";
}

function openModal() {
    document.querySelector(".quote-modal-background").style.display = "flex";
}

const priceInputs = document.querySelectorAll(".price-input input");

let sum = 0;
function addAllPrice() {
    sum = 0; 
    priceInputs.forEach((input) => {
        sum += parseFloat(input.value) || 0;
    });
    document.querySelector(".total-price").textContent = sum.toLocaleString('en-US');
}

priceInputs.forEach((input) => {
    input.addEventListener("change", addAllPrice);
});

const priceSetForm = document.querySelector('.modal-body');

priceSetForm.addEventListener('submit', (e) => {
    e.preventDefault();
    if (confirm("Are you sure the total price for this quote is " + sum.toLocaleString('en-US') + "?")) {
        priceSetForm.submit();
    } else {
        // Code to execute if user cancels
        // ...
    }
});