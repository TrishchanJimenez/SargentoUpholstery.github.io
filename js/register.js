const submitBtn = document.querySelector('.signUpBtn');
const passwordField = document.querySelector('input[name=password]');
const confirmPasswordField = document.querySelector('input[name=confirmPassword]');
const registrationForm = document.querySelector('#signUpForm');

const passwordErrorMesssage = document.querySelector(' .password');

const passwordShowToggle = document.querySelector('.toggle-password');
const toggleImg = document.querySelector('.toggle-img');
const toggleText = document.querySelector('.toggle-text');

submitBtn.addEventListener('click', (e) => {
    e.preventDefault();

    if(passwordField.value.length < 8) {
        // IF PASSWORD IS LESS THAN 8 CHARACTERS
        passwordErrorMesssage.innerText = "Use 8 or more characters with a mix of letters, numbers & symbols";
        passwordErrorMesssage.classList.remove('hide');
    } else if(passwordField.value != confirmPasswordField.value) {
        // IF PASSWORD AND CONFIRM PASSWORD DOES NOT MATCH
        passwordErrorMesssage.innerText = "Password does not match";
        passwordErrorMesssage.classList.remove('hide');
    } else {
        registrationForm.submit();
    }
})

passwordField.addEventListener('keyup', () => {
    if(!passwordErrorMesssage.classList.contains('hide')) {
        passwordErrorMesssage.classList.add('hide');
    }
})

passwordShowToggle.addEventListener('click', () => {
    passwordShowToggle.classList.toggle('show-pass');
    
    if(passwordShowToggle.classList.contains('show-pass')) {
        toggleImg.src = '/websiteimages/icons/show.svg';
        toggleText.innerText = "Hide";
        passwordField.setAttribute('type', 'text');
    } else {
        toggleImg.src = '/websiteimages/icons/hide.svg';
        toggleText.innerText = "Show";
        passwordField.setAttribute('type', 'password');
    }
})