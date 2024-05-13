const actionButtons = document.querySelector('.action-buttons')
const acceptOrder = actionButtons.querySelector(' .accept-order');
const rejectOrder = actionButtons.querySelector(' .reject-order');

const onAccept = document.querySelector('.on-accept');
const onReject = document.querySelector('.on-reject');

const formButtons = document.querySelector('.on-click');
const saveButton = formButtons.querySelector('input[type="submit"]');
const cancelButton = formButtons.querySelector('input[type="button"]');

const rejectionInput = onReject.querySelector('.rejection-input');
const priceInput = onAccept.querySelector('.price-input');

acceptOrder.addEventListener('click', (e) => {
    priceInput.required = true;
    rejectionInput.required = false;

    actionButtons.style.display = "none";
    onAccept.style.display = "block";
    formButtons.style.display = "block";
})

rejectOrder.addEventListener('click', (e) => {
    priceInput.required = false;
    rejectionInput.required = true;

    actionButtons.style.display = "none";
    onAccept.style.display = "none";
    onReject.style.display = "block";
    formButtons.style.display = "block";
})

cancelButton.addEventListener('click', (e) => {
    onReject.style.display = "none";
    onAccept.style.display = "none";
    formButtons.style.display = "none";
    actionButtons.style.display = "flex";
})