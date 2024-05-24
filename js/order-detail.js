    const actionButtons = document.querySelector('.order-action .action-buttons') ?? null;
    const acceptOrder = actionButtons.querySelector(' .accept-order');
    const rejectOrder = actionButtons.querySelector(' .reject-order');
    
    const onAccept = document.querySelector('.on-accept');
    const onReject = document.querySelector('.on-reject');
    
    const formButtons = document.querySelector('.on-click');
    const saveButton = formButtons.querySelector('input[type="submit"]');
    const cancelButton = formButtons.querySelector('input[type="button"]');
    
    const rejectionInput = onReject.querySelector('.rejection-input');
    const priceInput = onAccept.querySelector('.price-input');

    const verifyDownpaymentBtn = document.querySelector('.downpayment .accept-verification');
    const reverifyDownpaymentBtn = document.querySelector('.downpayment .reject-verification');
    const verifyFullpaymentBtn = document.querySelector('.fullpayment .accept-verification');
    const reverifyFullpaymentBtn = document.querySelector('.fullpayment .reject-verification');
    const downpaymentVerificationStatus = document.querySelector('.downpayment-status');
    const fullpaymentVerificationStatus = document.querySelector('.fullpayment-status');
    
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
    
    function verifyDownpayment() {
        let verificationData = new FormData();
        verificationData.append('order_id', orderId);
        verificationData.append('payment_phase', 'downpayment');
        verificationData.append('is_verified', true);
        console.log(verificationData);
        fetch('../api/payment_update_admin.php', {
            method: 'POST',
            body: verificationData })
            .then(response => {
                // console.log(response.text());
                return response.json()
            })
            .then(data => {
                downpaymentVerificationStatus.textContent = data.payment_status;
                document.querySelector('.verification-buttons.downpayment').display = 'none';
            })
    }
    
    function reverifyDownpayment() {
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