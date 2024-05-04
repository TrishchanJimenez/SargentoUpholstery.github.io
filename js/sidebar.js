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
    })
}


highlightActivePage();