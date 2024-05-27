document.addEventListener("DOMContentLoaded", function() {
    const cancelButton = document.querySelector(".quote-actions__cancel");
    const acceptButton = document.querySelector(".quote-actions__accept");

    if (cancelButton) {
        cancelButton.addEventListener("click", function() {
            if (confirm("Are you sure you want to cancel this quote request? This action cannot be undone.")) {
                updateQuoteStatus("cancelled");
            }
        });
    }

    if (acceptButton) {
        acceptButton.addEventListener("click", function() {
            if (confirm("Are you sure you want to accept this quote?")) {
                updateQuoteStatus("accepted");
            }
        });
    }

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