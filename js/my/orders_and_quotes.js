document.addEventListener("DOMContentLoaded", function() {
    const ordersTabButton = document.querySelector(".onq__tab-button--orders");
    const quotesTabButton = document.querySelector(".onq__tab-button--quotes");
    const ordersTab = document.querySelector(".onq__tab--orders");
    const quotesTab = document.querySelector(".onq__tab--quotes");
    // Function to show orders tab and hide quotes tab
    function showOrdersTab() {
        ordersTab.style.display = "flex";
        quotesTab.style.display = "none";
    }
    // Function to show quotes tab and hide orders tab
    function showQuotesTab() {
        ordersTab.style.display = "none";
        quotesTab.style.display = "flex";
    }
    // Initially show orders tab and hide quotes tab
    showQuotesTab();
    // Add event listener for orders tab button
    ordersTabButton.addEventListener("click", function() {
        showOrdersTab();
    });
    // Add event listener for quotes tab button
    quotesTabButton.addEventListener("click", function() {
        showQuotesTab();
    });
});