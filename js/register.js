const submitBtn = document.querySelector('.signUpBtn');
const passwordField = document.querySelector('input[name=password]');
const confirmPasswordField = document.querySelector('input[name=confirmPassword]');
const registrationForm = document.querySelector('#signUpForm');

const passwordErrorMesssage = document.querySelector(' .password');

const passwordShowToggle = document.querySelector('input[name="show-password"]');
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

confirmPasswordField.addEventListener('keyup', () => {
    if(!passwordErrorMesssage.classList.contains('hide')) {
        passwordErrorMesssage.classList.add('hide');
    }
})

passwordShowToggle.addEventListener('change', () => {
    if(passwordShowToggle.checked) {
        passwordField.setAttribute('type', 'text');
        confirmPasswordField.setAttribute('type', 'text');
    } else {
        passwordField.setAttribute('type', 'password');
        confirmPasswordField.setAttribute('type', 'password');
    }
})