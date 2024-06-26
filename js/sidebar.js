const links = document.querySelectorAll('.admin-nav .admin-link');

// GET CURRENT PAGE
const currentUrl = window.location.href;
const parts = currentUrl.split('/');
const lastPart = parts[parts.length - 1];
const currentPage = lastPart.split('.')[0];

function highlightActivePage() {
    links.forEach(element => {
        if(element.dataset.page === currentPage) {
            element.classList.add('active');
        }
        if(element.dataset.page === "orders" && currentPage === "order_details") {
            element.classList.add('active');
        }
        if(element.dataset.page === "content" && currentPage === "cms") {
            element.classList.add('active');
        }
        if(element.dataset.page === "quotations" && currentPage === "quotation_details") {
            element.classList.add('active');
        }
    })
}


highlightActivePage();