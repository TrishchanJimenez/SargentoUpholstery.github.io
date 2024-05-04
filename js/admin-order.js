const tableBody = document.querySelector('tbody');
let lastSelectedClosed = false;
let target;

tableBody.addEventListener('mousedown', (e) => {
    target = e.target.closest('.prod-status') ?? e.target.closest('.payment-status');
    if(target !== null && target.classList.contains('status')) {
        target.classList.remove('status');
        target.classList.add('active');


        if(target.classList.contains('prod-status')) {
            const selector = target.querySelector('select[name=select-prod-status]'); 
            const status = target.querySelector('span[data-prod-status]');
    
            selector.addEventListener('change', (e) => {
                // console.log('change')
                status.dataset.prodStatus = selector.value;
                status.innerText = selector.value.split('-').join(' ');
                target.classList.remove('active');
                target.classList.add('status');
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
    
            selector.addEventListener('change', (e) => {
                // console.log('change')
                status.dataset.payment = selector.value;
                status.innerText = selector.value.split('-').join(' ');

                target.classList.remove('active');
                target.classList.add('status');
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
