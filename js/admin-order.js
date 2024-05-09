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
    
            selector.addEventListener('change', (e) => {
                // console.log('change')
                status.dataset.prodStatus = selector.value;
                status.innerText = selector.value.split('-').join(' ');
                target.classList.remove('active');
                target.classList.add('status');

                const newStatus = status.dataset.prodStatus;

                const statusData = new FormData();
                statusData.append('order_id', orderId);
                statusData.append('new_status', newStatus);
                statusData.append('status_type', 'prod');

                fetch('./setter/update_status.php', {
                    method: "POST",
                    body: statusData
                })
                .then(res => {
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
                // console.log('change')
                status.dataset.prodStatus = selector.value;
                status.innerText = selector.value.split('-').join(' ');
                target.classList.remove('active');
                target.classList.add('status');
            })
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

                fetch('./setter/update_status.php', {
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
                status.dataset.payment = selector.value;
                status.innerText = selector.value.split('-').join(' ');

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