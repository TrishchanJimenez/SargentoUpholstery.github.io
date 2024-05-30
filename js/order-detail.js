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

const orderDetail = document.querySelector(' .order-detail-main');

orderDetail.addEventListener('click', (e) => {
    target = e.target.closest(".prod-status");
    console.log(target);
    if (target !== null && target.classList.contains("status")) {
        target.classList.remove("status");
        target.classList.add("active");
        if (target.classList.contains("prod-status")) {
            console.log(orderId);
            const selector = target.querySelector(
                "select[name=select-prod-status]"
            );
            const status = target.querySelector("span[data-prod-status]");
            console.log(status.dataset.prodStatus);

            selector.value = status.dataset.prodStatus;
            const pastStatus = status.dataset.prodStatus;

            selector.addEventListener("change", (e) => {
                // console.log('change')
                status.dataset.prodStatus = selector.value;
                status.innerText = selector.value.split("-").join(" ");
                target.classList.remove("active");
                target.classList.add("status");

                const newStatus = status.dataset.prodStatus;
                const statuses = {
                    "pending-downpayment": "Pending Downpayment",
                    "awaiting-furniture": "Awaiting Furniture",
                    "in-production": "In Production",
                    "pending-fullpayment": "Pending Fullpayment",
                    "out-for-delivery": "Out for Delivery",
                    received: "Received",
                };

                const statusKeys = Object.keys(statuses);
                const currentIndex = statusKeys.indexOf(newStatus);
                const pastIndex = statusKeys.indexOf(pastStatus);
                const previousStatuses = statusKeys.slice(
                    pastIndex,
                    currentIndex
                );

                console.log(currentIndex);
                console.log(pastIndex);
                console.log(previousStatuses);
                if (previousStatuses.length >= 2) {
                    const confirmation = confirm(
                        "Are you sure you want to skip multiple stages?"
                    );
                    if (!confirmation) {
                        status.dataset.prodStatus = pastStatus;

                        if (status.dataset.prodStatus === "") {
                            status.dataset.prodStatus = "new-order";
                            status.innerText = "New Order";
                        } else {
                            status.innerText = selector.value
                                .split("-")
                                .join(" ");
                        }
                        target.classList.remove("active");
                        target.classList.add("status");
                        return;
                    }
                }

                // Continue with the rest of the code...

                const statusData = new FormData();
                statusData.append("order_id", orderId);
                statusData.append("new_status", newStatus);
                statusData.append("status_type", "prod");
                console.log(statusData);
                fetch("/api/update_status.php", {
                    method: "POST",
                    body: statusData,
                })
                    .then((res) => {
                        if (!res.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return res.json();
                    })
                    .then((data) => {
                        // Handle the response data here
                        console.log("Response:", data);
                    })
                    .catch((error) => {
                        console.error("Error: ", error);
                    });
            });
            selector.addEventListener("blur", (e) => {
                // console.log('change')
                status.dataset.prodStatus = selector.value;

                if (status.dataset.prodStatus === "") {
                    status.dataset.prodStatus = "new-order";
                    status.innerText = "New Order";
                } else {
                    status.innerText = selector.value.split("-").join(" ");
                }
                target.classList.remove("active");
                target.classList.add("status");
            });
        }
    }
});