const tableBody = document.querySelector('tbody');
let lastSelectedClosed = false;
let target;

tableBody.addEventListener('mousedown', (e) => {
    target = e.target.closest('.prod-status') ?? e.target.closest('.payment-status');
    if(target !== null && target.classList.contains('status')) {
        target.classList.remove('status');
        target.classList.add('active');
        const orderId = target.closest('tr').dataset.id;

        if(target.classList.contains('prod-status')) {
            const selector = target.querySelector('select[name=select-prod-status]'); 
            const status = target.querySelector('span[data-prod-status]');

            selector.value = status.dataset.prodStatus;
            const pastStatus = status.dataset.prodStatus;
    
            selector.addEventListener('change', (e) => {
                // console.log('change')
                status.dataset.prodStatus = selector.value;
                status.innerText = selector.value.split('-').join(' ');
                target.classList.remove('active');
                target.classList.add('status');

                const newStatus = status.dataset.prodStatus;
                const statuses = {
                    "new-order": "New Order",
                    "pending-downpayment": "Pending Downpayment",
                    "ready-for-pickup": "Ready for Pickup",
                    "in-production": "In Production",
                    "pending-fullpayment": "Pending Fullpayment",
                    "out-for-delivery": "Out for Delivery",
                    "received": "Received"
                };

                const statusKeys = Object.keys(statuses);
                const currentIndex = statusKeys.indexOf(newStatus);
                const pastIndex = statusKeys.indexOf(pastStatus);
                const previousStatuses = statusKeys.slice(pastIndex, currentIndex);

                console.log(previousStatuses);
                if (previousStatuses.length >= 2) {
                    const confirmation = confirm("Are you sure you want to skip multiple stages?");
                    if (!confirmation) {
                        return;
                    }
                }

                // Continue with the rest of the code...

                // const statusData = new FormData();
                // statusData.append('order_id', orderId);
                // statusData.append('new_status', newStatus);
                // statusData.append('status_type', 'prod');
                // console.log(statusData);
                  // fetch('/api/update_status.php', {
                //     method: "POST",
                //     body: statusData
                // })
                // .then(res => {
                //     if (!res.ok) {
                //         throw new Error('Network response was not ok');
                //     }
                //     return res.json();
                // }).then(data => {
                //     // Handle the response data here
                //     console.log('Response:', data);
                // }).catch(error => {console.error('Error: ', error)})
            })
            // selector.addEventListener('blur', (e) => {
            //     // console.log('change')
            //     status.dataset.prodStatus = selector.value;

            //     if(status.dataset.prodStatus === '') {
            //         status.dataset.prodStatus = 'new-order';
            //         status.innerText = 'New Order';
            //     } else {
            //         status.innerText = selector.value.split('-').join(' ');
            //     }
            //     target.classList.remove('active');
            //     target.classList.add('status');
            // })
        } else if(target.classList.contains('payment-status')) {
            const selector = target.querySelector('select[name=select-payment-status]'); 
            const status = target.querySelector('span[data-payment]');

            // console.log(status.dataset.payment);
            selector.value = status.dataset.payment;
            // console.log()
    
            selector.addEventListener('change', (e) => {
                // console.log('change')
                status.dataset.payment = selector.value;
                status.innerText = selector.value.split('-').join(' ');

                target.classList.remove('active');
                target.classList.add('status');

                const newStatus = status.dataset.payment;
                console.log(orderId, " ", newStatus, " ", "payment");
                const statusData = new FormData();
                statusData.append('order_id', orderId);
                statusData.append('new_status', newStatus);
                statusData.append('status_type', 'payment');

                fetch('/api/update_status.php', {
                    method: "POST",
                    body: statusData
                }).then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                }).then(data => {
                    // Handle the response data here
                    // console.log('Response:', data);
                }).catch(error => {console.error('Error: ', error)})
            })
            selector.addEventListener('blur', (e) => {
                if(status.dataset.prodStatus === '') {
                    status.dataset.payment = 'unpaid';
                    status.innerText = 'Unpaid';
                } else {
                    status.dataset.payment = selector.value;
                    status.innerText = selector.value.split('-').join(' ');
                }
                target.classList.remove('active');
                target.classList.add('active');
                // console.log('change')
            })
        }
    }
})


// SET FILTER PREVIOUS VALUES;
function setFilterValues() {
    // Retrieve the GET values from the URL
    const urlParams = new URLSearchParams(window.location.search);

    // Set the selected value for 'search-order'
    const searchOrder = urlParams.get('search-order');
    if (searchOrder) {
        document.querySelector(`select[name="search-order"]`).value = searchOrder;
    }

    // Set the value for 'search-input'
    const searchInput = urlParams.get('search-input');
    if (searchInput) {
        document.querySelector(`input[name="search-input"]`).value = searchInput;
    }

    // Set the selected value for 'order-type'
    const orderType = urlParams.get('order-type');
    if (orderType) {
        document.querySelector(`select[name="order-type"]`).value = orderType;
    }

    // Set the selected value for 'order-prod-status'
    const orderProdStatus = urlParams.get('order-prod-status');
    if (orderProdStatus) {
        document.querySelector(`select[name="order-prod-status"]`).value = orderProdStatus;
    }

    // Set the selected value for 'order-payment-status'
    const orderPaymentStatus = urlParams.get('order-payment-status');
    if (orderPaymentStatus) {
        document.querySelector(`select[name="order-payment-status"]`).value = orderPaymentStatus;
    }

    // Set the selected value for 'order-sort'
    const orderSort = urlParams.get('order-sort');
    if (orderSort) {
        document.querySelector(`select[name="order-sort"]`).value = orderSort;
    }
}

setFilterValues();

const filterForm = document.querySelector('.order-filters');
const filterSelectors = filterForm.querySelectorAll('select');
const searchType = filterForm.querySelector('select[name="search-order"]');
const searchInput = filterForm.querySelector('input[name="search-input"]');

filterForm.addEventListener('submit', (e) => {
    e.preventDefault();
    if(searchInput.value === '') {
        searchType.disabled = true;
        searchInput.disabled = true;
    }
    filterSelectors.forEach((selector) => {
        if(selector.value === 'default') {
            selector.disabled = true;
        }
    });

    filterForm.submit();
});

let lastSelectedCheckbox = null;
const multipleSelector = document.querySelector('.selected-multiple');  
const selectedCountDisplay = multipleSelector.querySelector('.selected-count');
const multipleSelectorCloseBtn = multipleSelector.querySelector('.close-icon');

tableBody.addEventListener('click', (e) => {
    const checkbox = e.target.closest('input[type="checkbox"]');
    if (checkbox && e.shiftKey && lastSelectedCheckbox) {
        const checkboxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]'));
        const startIndex = checkboxes.indexOf(lastSelectedCheckbox);
        const endIndex = checkboxes.indexOf(checkbox);
        const [start, end] = startIndex < endIndex ? [startIndex, endIndex] : [endIndex, startIndex];
        checkboxes.slice(start, end + 1).forEach((cb) => {
            cb.checked = true;
        });
    } 
    else if (checkbox && !checkbox.checked && lastSelectedCheckbox && e.shiftKey) {
        const checkboxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]'));
        const startIndex = checkboxes.indexOf(lastSelectedCheckbox);
        const endIndex = checkboxes.indexOf(checkbox);
        const [start, end] = startIndex < endIndex ? [startIndex, endIndex] : [endIndex, startIndex];
        checkboxes.slice(start, end + 1).forEach((cb) => {
            cb.checked = false;
        });
    }
    if (checkbox) {
        const selectedCheckboxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]:checked'));
        console.log(selectedCheckboxes.length);
        if(multipleSelector.style.display !== 'flex' && selectedCheckboxes.length > 0) {
            multipleSelector.style.display = 'flex';
        } else if(selectedCheckboxes.length === 0) {
            multipleSelector.style.display = 'none';
        }
        selectedCountDisplay.innerText = selectedCheckboxes.length;
    }
    lastSelectedCheckbox = checkbox;
});

multipleSelectorCloseBtn.addEventListener('click', (e) => {
    const selectedCheckboxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]:checked'));
    selectedCheckboxes.forEach((cb) => {
        cb.checked = false;
    });
    multipleSelector.style.display = 'none';
    selectedCountDisplay.innerText = 0;
});

const advanceNextBtn = document.querySelector('.advance-next');
advanceNextBtn.addEventListener('click', (e) => {
    const selectedCheckboxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]:checked'));
    const orderIds = selectedCheckboxes.map((cb) => cb.closest('tr').dataset.id);
    const statusData = new FormData();
    if (orderIds.length > 0) {
        const confirmDialog = confirm("Are you sure you want to advance these orders?");
        if (confirmDialog) {
            // Proceed with advancing the orders
            orderIds.forEach(id => statusData.append('order_id[]', id));
            statusData.append('status_type', 'prod');
            statusData.append('is_multiple', true);
            console.log(statusData);
            fetch('/api/update_status.php', {
                method: "POST",
                body: statusData
            }).then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                // console.log(res.text());
                return res.json();
            }).then(data => {
                // Handle the response data here
                console.log('Response:', data);
                // window.location.reload();
            }).catch(error => {
                console.error('Error: ', error);
            });
        }
    }
});