document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');

    const registerPassword = document.getElementById('registerPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const errorMessage = document.querySelector('#registerForm .error-message');

    // ---------- Show form based on URL hash ----------
    if (window.location.hash === '#register') {
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
    } else {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
    }

    // ---------- Toggle between login and register ----------
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
            errorMessage.textContent = "";
            errorMessage.style.display = "none";

            window.location.hash = registerForm.classList.contains('active') ? 'register' : '';
        });
    });

    // ---------- Toggle password visibility ----------
    togglePasswordIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // ---------- Password validation ----------
    function validatePassword(password) {
        if (!/.{8,}/.test(password)) return "Password must be at least 8 characters long.";
        if (!/[A-Z]/.test(password)) return "Password must contain at least 1 uppercase letter.";
        if (!/[a-z]/.test(password)) return "Password must contain at least 1 lowercase letter.";
        if (!/[0-9]/.test(password)) return "Password must contain at least 1 number.";
        if (!/[@$!%*?&#]/.test(password)) return "Password must contain at least 1 special character (@$!%*?&#).";
        return "";
    }

    // ---------- Live confirm password check ----------
    confirmPassword.addEventListener('input', () => {
        if (confirmPassword.value !== registerPassword.value) {
            showError("Passwords do not match!");
        } else {
            hideError();
        }
    });

    // ---------- Live password validation ----------
    registerPassword.addEventListener('input', () => {
        const message = validatePassword(registerPassword.value);

        if (message) {
            showError(message);
        } else if (confirmPassword.value && confirmPassword.value !== registerPassword.value) {
            showError("Passwords do not match!");
        } else {
            hideError();
        }
    });

    // ---------- Form submit check ----------
    registerForm.addEventListener('submit', (e) => {
        const message = validatePassword(registerPassword.value);

        if (message || confirmPassword.value !== registerPassword.value) {
            e.preventDefault();
            showError(message || "Passwords do not match!");
        }
    });

    // ---------- Helper functions ----------
    function showError(msg) {
        errorMessage.textContent = msg;
        errorMessage.style.display = "block";
        errorMessage.style.animation = "shake 0.5s";
        setTimeout(() => { errorMessage.style.animation = ""; }, 500);
    }

    function hideError() {
        errorMessage.textContent = "";
        errorMessage.style.display = "none";
    }
});
