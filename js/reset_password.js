const emailNotif = document.querySelector('.email-notif');
function showEmailNotif() {
    emailNotif.style.display = "block";
}

const changePasswordForm = document.querySelector('.change-password-form');
const showCheckbox = document.querySelector('.show-pass-checkbox input[type="checkbox"]');
const passwordInput = document.querySelector('input[name="new-password"]');
const confirmPasswordInput = document.querySelector('input[name="confirm-password"]');

showCheckbox.addEventListener('change', (e) => {
    if(showCheckbox.checked) {
        passwordInput.type = 'text';
        confirmPasswordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
        confirmPasswordInput.type = 'password';
    }
})

const errorMessage = document.querySelector('.error-message');

changePasswordForm.addEventListener('submit', (e) => {
    if (passwordInput.value !== confirmPasswordInput.value) {
        e.preventDefault();
        errorMessage.style.display = 'block';
        // alert('The passwords do not match.');
    }
});

passwordInput.addEventListener('input', () => {
    errorMessage.style.display = 'none';
});

confirmPasswordInput.addEventListener('input', () => {
    errorMessage.style.display = 'none';
});