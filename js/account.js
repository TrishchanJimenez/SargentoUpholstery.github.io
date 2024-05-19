const profileContainer = document.querySelector('.profile-container');
const addressContainer = profileContainer.querySelector('.all-address');

profileContainer.addEventListener('click', (e) => {
    console.log(e.target);
    if (e.target.matches('td > .edit-icon')) {
        const span = e.target.previousElementSibling;
        const input = document.createElement('input');
        const infoType = e.target.closest('td').dataset.type;
        input.type = 'text';
        input.value = span.textContent;
        const pastValue = input.value;
        span.parentElement.replaceChild(input, span);
        input.focus();
        input.addEventListener('blur', () => {
            const span = document.createElement('span');
            if (infoType === 'contact_number' && !/^\d{11}$/.test(input.value)) {
                span.textContent = pastValue;
            } else if (infoType === 'email' && !((/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/).test(input.value))) {
                span.textContent = pastValue;
            } else if (input.value === '') {
                span.textContent = pastValue;
            } else if (input.value === pastValue) {
                span.textContent = input.value;
            } else {
                span.textContent = input.value;
                const newUserInfo = new FormData();
                newUserInfo.append('info_type', infoType);
                newUserInfo.append('new_value', input.value);
                fetch('../api/update_user_info.php', {
                    method: 'POST',
                    body: newUserInfo
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response data here
                })
                .catch(error => {
                    // Handle any errors here
                });
            }
            // span.textContent = input.value;
            input.parentElement.replaceChild(span, input);
            return;
        });
    }

    if (e.target.matches('.action-icons > .edit-icon')) {
        const addressContainer = e.target.closest('.address');
        const span = addressContainer.querySelector('span');
        const input = document.createElement('input');
        const addressId = addressContainer.dataset.addressId;
        input.type = 'text';
        input.value = span.textContent;
        const pastValue = input.value;
        span.parentElement.replaceChild(input, span);
        input.focus();
        input.addEventListener('blur', () => {
            const span = document.createElement('span');
            if (input.value === '') {
                span.textContent = pastValue;
            } else if (input.value === pastValue) {
                span.textContent = input.value;
            } else {
                span.textContent = input.value;
                const newAddressInfo = new FormData();
                newAddressInfo.append('address_id', addressId);
                newAddressInfo.append('new_address', input.value);
                newAddressInfo.append('action', "UPDATE");
                console.log(newAddressInfo);
                fetch('../api/Address.php', {
                    method: 'POST',
                    body: newAddressInfo
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response data here
                })
                .catch(error => {
                    // Handle any errors here
                });
            }
            input.parentElement.replaceChild(span, input);
            return;
        });
    }

    if (e.target.closest('.add-address')) {
        const input = document.createElement('input');
        input.type = 'text';
        allAddress.appendChild(input);
        input.classList.add('address');
        input.focus();
        input.addEventListener('blur', () => {
            if (input.value !== '') {
                const newAddressInfo = new FormData();
                newAddressInfo.append('new_address', input.value);
                newAddressInfo.append('action', "CREATE");
                fetch('../api/Address.php', {
                    method: 'POST',
                    body: newAddressInfo
                })
                .then(response => response.json())
                .then(data => {
                    // RETURNS address_id and address
                    // Handle the response data here
                    const addressId = data.address_id;
                    const address = data.address;
                    allAddress.removeChild(input);
                    allAddress.innerHTML += `
                        <div class="address" data-address-id="${addressId}">
                            <span>${address}</span>
                            <div class="action-icons">
                                <img src="/websiteimages/icons/edit-icon.svg" alt="" class="edit-icon">
                                <img src="/websiteimages/icons/remove-icon.svg" alt="" class="delete-icon">
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.log(error);
                });
            } else {
                allAddress.removeChild(input);
            }
        });
    }

    if (e.target.matches('.delete-icon')) {
        const addressSelected = e.target.closest('.address');
        const addressId = addressSelected.dataset.addressId;
        const confirmDelete = confirm('Are you sure you want to delete this address?');
        if (confirmDelete) {
            const deleteAddressInfo = new FormData();
            deleteAddressInfo.append('address_id', addressId);
            deleteAddressInfo.append('action', 'DELETE');
            fetch('../api/Address.php', {
                method: 'POST',
                body: deleteAddressInfo
            })
            .then(response => response.json())
            .then(data => {
                // console.log(data);
                // const addressToRemove = addressContainer.querySelector(`.address[data-address-id="${data.address_id}"]`);
                // addressToRemove.remove();
                addressSelected.remove();
            })
            .catch(error => {
                // Handle any errors here
            });
        }
    }
});

const allAddress = document.querySelector('.all-address');
const addAddress = document.querySelector('.add-address');

if (allAddress.children.length === 0) {
    addAddress.style.marginTop = '0';
}