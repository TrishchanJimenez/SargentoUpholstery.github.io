document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const eyeClosed = document.getElementById('eyeClosed');
    const eyeOpen = document.getElementById('eyeOpen');
    const showText = document.getElementById('showText');
    const hideText = document.getElementById('hideText');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        eyeClosed.style.display = type === 'password' ? 'block' : 'none';
        eyeOpen.style.display = type === 'password' ? 'none' : 'block';
        showText.style.display = type === 'password' ? 'block' : 'none';
        hideText.style.display = type === 'password' ? 'none' : 'block';
    });
});