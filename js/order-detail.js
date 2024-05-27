const actionButtons = document.querySelector('.order-action .action-buttons') ?? null;
if(actionButtons !== null) {
    const acceptOrder = actionButtons.querySelector(' .accept-order');
    const rejectOrder = actionButtons.querySelector(' .reject-order');

    const onAccept = document.querySelector('.on-accept');
    const onReject = document.querySelector('.on-reject');

    const formButtons = document.querySelector('.on-click');
    const saveButton = formButtons.querySelector('input[type="submit"]');
    const cancelButton = formButtons.querySelector('input[type="button"]');

    const rejectionInput = onReject.querySelector('.rejection-input');
    const priceInput = onAccept.querySelector('.price-input');

    const isAcceptedInput = document.querySelector('[name="is_accepted"]');
    const orderAcceptForm = document.querySelector('#order-accept-form');

    acceptOrder.addEventListener('click', (e) => {
        priceInput.required = true;
        rejectionInput.required = false;
    
        actionButtons.style.display = "none";
        onAccept.style.display = "block";
        formButtons.style.display = "block";
        isAcceptedInput.value = true;
        console.log(isAcceptedInput.value);
    })
    
    rejectOrder.addEventListener('click', (e) => {
        priceInput.required = false;
        rejectionInput.required = true;
    
        actionButtons.style.display = "none";
        onAccept.style.display = "none";
        onReject.style.display = "block";
        formButtons.style.display = "block";
        isAcceptedInput.value = false;
        console.log(isAcceptedInput.value);
    })

    cancelButton.addEventListener('click', (e) => {
        onReject.style.display = "none";
        onAccept.style.display = "none";
        formButtons.style.display = "none";
        actionButtons.style.display = "flex";
    })

    orderAcceptForm.addEventListener("submit", (e) => {
      e.preventDefault();
      isAcceptedInput.value = isAcceptedInput.value === "true" ? true : false;

      orderAcceptForm.submit();
    });
}

function verifyFullpayment() {
    let verificationData = new FormData();
    verificationData.append('order_id', orderId);
    verificationData.append('payment_phase', 'downpayment');
    console.log(verificationData);
    fetch('../api/payment_update_admin.php', {
        method: 'POST',
        body: verificationData })
        .then(response => response.json())
        .then(data => {
            downpaymentVerificationStatus.textContent = data.status;
        })
}

function reverifyFullpayment() {
    let verificationData = new FormData();
    verificationData.append('order_id', orderId);
    verificationData.append('payment_phase', 'downpayment');
    console.log(verificationData);
    fetch('../api/payment_update_admin.php', {
        method: 'POST',
        body: verificationData })
        .then(response => response.json())
        .then(data => {
            downpaymentVerificationStatus.textContent = data.status;
        })
}

const downpaymentInfo = document.querySelector('.downpayment-info');
const fullpaymentInfo = document.querySelector('.fullpayment-info');

if (downpaymentInfo !== null) {
    const verifyDownpaymentBtn = document.querySelector('.downpayment .accept-verification');
    const reverifyDownpaymentBtn = document.querySelector('.downpayment .reject-verification');
    const downpaymentVerificationStatus = document.querySelector('.downpayment-status');
    
    verifyDownpaymentBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        console.log("verify downpayment");
        let verificationData = new FormData();
        verificationData.append("order_id", orderId);
        verificationData.append("payment_phase", "downpayment");
        verificationData.append("is_verified", true);
        console.log(verificationData);
        fetch("/api/payment_update_admin.php", {
            method: "POST",
            body: verificationData,
        })
        .then((response) => {
            // console.log(response.text());
            return response.json();
        })
        .then((data) => {
            console.log(data);
            downpaymentVerificationStatus.textContent = data.payment_status;
            document.querySelector(".verification-buttons.downpayment").display = "none";
            document.querySelector(".payment-status").dataset.payment = "partially-paid";
            document.querySelector(".payment-status").innerText = "Partially Paid";
        });
    });

    reverifyDownpaymentBtn.addEventListener('click', (e) => {
        let verificationData = new FormData();
        verificationData.append('order_id', orderId);
        verificationData.append('payment_phase', 'downpayment');
        console.log(verificationData);
        fetch('../api/payment_update_admin.php', {
            method: 'POST',
            body: verificationData })
            .then(response => response.json())
            .then(data => {
                downpaymentVerificationStatus.textContent = data.status;
            })
    });
}

if (fullpaymentInfo !== null) {
    const verifyFullpaymentBtn = document.querySelector('.fullpayment .accept-verification');
    const reverifyFullpaymentBtn = document.querySelector('.fullpayment .reject-verification');
    const fullpaymentVerificationStatus = document.querySelector('.fullpayment-status');

    fullpaymentInfo.querySelector('.verify-button').addEventListener('click', verifyFullpayment);
    fullpaymentInfo.querySelector('.reverify-button').addEventListener('click', reverifyFullpayment);
}