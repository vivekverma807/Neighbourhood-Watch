// Automatically convert username to uppercase using JavaScript
const usernameInput = document.getElementById('username');
usernameInput.addEventListener('input', function () {
    this.value = this.value.toUpperCase();
});

// CAPTCHA generation and validation
const captchaElement = document.getElementById('captcha');
const captchaInput = document.getElementById('captchaInput');
const refreshCaptcha = document.getElementById('refreshCaptcha');
const submitButton = document.getElementById('submitButton');

// Generate a random CAPTCHA
function generateCaptcha() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let captcha = '';
    for (let i = 0; i < 5; i++) { // 5 characters CAPTCHA
        captcha += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return captcha;
}

// Set the initial CAPTCHA
let captcha = generateCaptcha();
captchaElement.textContent = captcha;

// Refresh CAPTCHA on button click
refreshCaptcha.addEventListener('click', function () {
    captcha = generateCaptcha();
    captchaElement.textContent = captcha;
});

// Validate CAPTCHA before submitting
submitButton.addEventListener('click', function (event) {
    if (captchaInput.value !== captcha) {
        event.preventDefault();
        alert('Incorrect Captcha. Please try again.');
        captcha = generateCaptcha();
        captchaElement.textContent = captcha; // Refresh CAPTCHA automatically
        captchaInput.value = ''; // Clear CAPTCHA input
    }
});

// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.classList.toggle('bx-show');
    this.classList.toggle('bx-hide');
});

document.addEventListener("DOMContentLoaded", () => {
    // Initial CAPTCHA generation and storage in sessionStorage
    const captcha = generateCaptcha();
    captchaElement.textContent = captcha;
    sessionStorage.setItem("captcha", captcha);
});