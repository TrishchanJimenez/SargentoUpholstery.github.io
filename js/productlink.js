const productContainer = document.querySelector('.product-categories');
let url = "./services_works.html";

productContainer.addEventListener('click', (e) => {
    if(e.target.closest('a') !== null) {
        const category = e.target.closest('a').id.split('-')[1];
        url = url + "?" + "category=" + category;
        window.location.href = url;
    }
})  