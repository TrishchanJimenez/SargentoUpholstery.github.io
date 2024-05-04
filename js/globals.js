//Navigation Menu for small screen
const openBtn = document.getElementById('open-btn');
const closeBtn = document.getElementById('close-btn');
const menu = document.getElementById('offcanvas-menu')

openBtn.addEventListener('click', (e) => {
    menu.classList.add('active');
});

closeBtn.addEventListener('click', (e) => {
    menu.classList.remove('active');
});

const accountBtn = document.getElementById('account-btn');
const accountMenu = document.querySelector('.account-menu');
accountBtn.addEventListener('click', () => {
    if(accountBtn.parentElement.classList.contains('nav-icons')) {
        accountMenu.classList.toggle('show');
    }
})