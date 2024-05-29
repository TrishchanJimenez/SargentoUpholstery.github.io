document.addEventListener('DOMContentLoaded', function () {
    const acceptModal = document.getElementById('acceptModal');
    const cancelModal = document.getElementById('cancelModal');
    const confirmAcceptAction = document.getElementById('confirmAcceptAction');
    const cancelAcceptAction = document.getElementById('cancelAcceptAction');
    const confirmCancelAction = document.getElementById('confirmCancelAction');
    const cancelCancelAction = document.getElementById('cancelCancelAction');

    function openModal(action) {
        if (action === 'accept') {
            acceptModal.style.display = 'block';
        } else if (action === 'cancel') {
            cancelModal.style.display = 'block';
        }
    }

    function closeModal() {
        acceptModal.style.display = 'none';
        cancelModal.style.display = 'none';
    }

    cancelAcceptAction.addEventListener('click', closeModal);
    cancelCancelAction.addEventListener('click', closeModal);

    confirmAcceptAction.addEventListener('click', function () {
        updateQuoteStatus('accepted');
        closeModal();
    });

    confirmCancelAction.addEventListener('click', function () {
        updateQuoteStatus('cancelled');
        closeModal();
    });

    // Expose openModal to global scope
    window.openModal = openModal;

    function updateQuoteStatus(status) {
        fetch("../api/update_quote_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `quote_id=${quoteId}&status=${status}`
        })
        .then(response => {
            if (response.ok) {
                alert("Quote status updated successfully.");
                location.reload();
            } else {
                alert("Failed to update quote status.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Failed to update quote status.");
        });
    }
});
