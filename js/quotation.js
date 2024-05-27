const actionButtons = document.querySelector(".order-action .action-buttons") ?? null;
if (actionButtons !== null) {
    const acceptOrder = actionButtons.querySelector(" .accept-order");

    const onAccept = document.querySelector(".on-accept");

    const formButtons = document.querySelector(".on-click");
    const saveButton = formButtons.querySelector('input[type="submit"]');
    const cancelButton = formButtons.querySelector('input[type="button"]');

    const priceInput = onAccept.querySelector(".price-input");
    const orderAcceptForm = document.querySelector("#order-accept-form");

    acceptOrder.addEventListener("click", (e) => {
        priceInput.required = true;

        actionButtons.style.display = "none";
        onAccept.style.display = "block";
        formButtons.style.display = "block";
        isAcceptedInput.value = true;
        console.log(isAcceptedInput.value);
    });

    cancelButton.addEventListener("click", (e) => {
        onAccept.style.display = "none";
        formButtons.style.display = "none";
        actionButtons.style.display = "flex";
    });
}