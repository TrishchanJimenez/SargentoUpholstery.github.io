const actionButtons = document.querySelector('.order-action .action-buttons') ?? null;
if(actionButtons !== null) {
    const acceptOrder = actionButtons.querySelector(' .accept-order');
    const rejectOrder = actionButtons.querySelector(' .reject-order');

    const onReject = document.querySelector('.on-reject');

    const formButtons = document.querySelector('.on-click');
    const saveButton = formButtons.querySelector('input[type="submit"]');
    const cancelButton = formButtons.querySelector('input[type="button"]');

    const rejectionInput = onReject.querySelector('.rejection-input');

    const orderAcceptForm = document.querySelector('#order-accept-form');

    rejectOrder.addEventListener('click', (e) => {
        rejectionInput.required = true;
    
        actionButtons.style.display = "none";
        onReject.style.display = "block";
        formButtons.style.display = "block";
    })

    cancelButton.addEventListener('click', (e) => {
        onReject.style.display = "none";
        formButtons.style.display = "none";
        actionButtons.style.display = "flex";
    })

    // orderAcceptForm.addEventListener("submit", (e) => {
    //   e.preventDefault();
    //   isAcceptedInput.value = isAcceptedInput.value === "true" ? true : false;

    //   orderAcceptForm.submit();
    // });
}

function verifyFullpayment() {
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
    
    if(verifyDownpaymentBtn !== null) {
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
                console.log(response.text());
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
            verificationData.append('payment_phase', 'fullpayment');
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
}

if (fullpaymentInfo !== null) {
    const verifyFullpaymentBtn = document.querySelector('.fullpayment .accept-verification');
    const reverifyFullpaymentBtn = document.querySelector('.fullpayment .reject-verification');
    const fullpaymentVerificationStatus = document.querySelector('.fullpayment-status');

    if (verifyFullpaymentBtn !== null) {
        verifyFullpaymentBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            console.log("verify downpayment");
            let verificationData = new FormData();
            verificationData.append("order_id", orderId);
            verificationData.append("payment_phase", "fullpayment");
            verificationData.append("is_verified", true);
            console.log(verificationData);
            fetch("/api/payment_update_admin.php", {
                method: "POST",
                body: verificationData,
            })
                .then((response) => {
                    console.log(response.text());
                    return response.json();
                })
                .then((data) => {
                    console.log(data);
                    downpaymentVerificationStatus.textContent =
                        data.payment_status;
                    document.querySelector(
                        ".verification-buttons.downpayment"
                    ).display = "none";
                    document.querySelector(".payment-status").dataset.payment =
                        "partially-paid";
                    document.querySelector(".payment-status").innerText =
                        "Partially Paid";
                });
        });

        reverifyDownpaymentBtn.addEventListener("click", (e) => {
            let verificationData = new FormData();
            verificationData.append("order_id", orderId);
            verificationData.append("payment_phase", "downpayment");
            console.log(verificationData);
            fetch("../api/payment_update_admin.php", {
                method: "POST",
                body: verificationData,
            })
                .then((response) => response.json())
                .then((data) => {
                    downpaymentVerificationStatus.textContent = data.status;
                });
        });
    }
    fullpaymentInfo.querySelector('.accept-verification').addEventListener('click', () => {
        let verificationData = new FormData();
        verificationData.append("order_id", orderId);
        verificationData.append("payment_phase", "fullpayment");
        console.log(verificationData);
        fetch("../api/payment_update_admin.php", {
            method: "POST",
            body: verificationData,
        })
            .then((response) => response.json())
            .then((data) => {
                downpaymentVerificationStatus.textContent = data.status;
            });
    });
    fullpaymentInfo.querySelector('.reject-verification').addEventListener('click', reverifyFullpayment);
}

const toggleDisplayFurnitureBtn = document.querySelector( ".toggle-furniture-display");
if(toggleDisplayFurnitureBtn !== null) {
    toggleDisplayFurnitureBtn.addEventListener('click', toggleFurnitureDisplay);
}

function toggleFurnitureDisplay() {
    if(toggleDisplayFurnitureBtn.innerText === "SHOW FURNITURE DETAILS") {
        toggleDisplayFurnitureBtn.textContent = "HIDE FURNITURE DETAILS";
    } else {
        toggleDisplayFurnitureBtn.textContent = "SHOW FURNITURE DETAILS";
    }

    document.querySelectorAll(' .item-detail').forEach(item => {
        item.classList.toggle("hidden");
    });
}