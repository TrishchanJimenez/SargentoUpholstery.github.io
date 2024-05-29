const actionButtons = document.querySelector(".order-action .action-buttons") ?? null;
if (actionButtons !== null) {
    // const acceptOrder = actionButtons.querySelector(" .accept-order");

    // const onAccept = document.querySelector(".on-accept");

    // const formButtons = document.querySelector(".on-click");
    // const saveButton = formButtons.querySelector('input[type="submit"]');
    // const cancelButton = formButtons.querySelector('input[type="button"]');

    // const priceInput = onAccept.querySelector(".price-input");
    // const orderAcceptForm = document.querySelector("#order-accept-form");

    // acceptOrder.addEventListener("click", (e) => {
    //     priceInput.required = true;

    //     actionButtons.style.display = "none";
    //     onAccept.style.display = "block";
    //     formButtons.style.display = "block";
    //     isAcceptedInput.value = true;
    //     console.log(isAcceptedInput.value);
    // });

    // cancelButton.addEventListener("click", (e) => {
    //     onAccept.style.display = "none";
    //     formButtons.style.display = "none";
    //     actionButtons.style.display = "flex";
    // });
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