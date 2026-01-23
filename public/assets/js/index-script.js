document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');

    const registerPassword = document.getElementById('registerPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const errorMessage = document.querySelector('#registerForm .error-message');
    const registerSubmit = document.getElementById('registerSubmit');
    const passwordRulesBox = document.getElementById('passwordRules');

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

    // ---------- Show/hide password rules ----------
    const allInputs = registerForm.querySelectorAll('input');

    registerPassword.addEventListener('focus', () => {
        passwordRulesBox.classList.add('show');
    });

    allInputs.forEach(input => {
        if (input !== registerPassword) {
            input.addEventListener('focus', () => {
                passwordRulesBox.classList.remove('show');
            });
        }
    });

    // ---------- Password rules ----------
    const rules = {
        length: document.getElementById('rule-length'),
        uppercase: document.getElementById('rule-uppercase'),
        lowercase: document.getElementById('rule-lowercase'),
        number: document.getElementById('rule-number'),
        special: document.getElementById('rule-special')
    };

    function allRulesValid() {
        return Object.values(rules).every(rule => rule.classList.contains('valid')) &&
               registerPassword.value === confirmPassword.value;
    }

    // ---------- Live password validation ----------
    registerPassword.addEventListener('input', () => {
        const val = registerPassword.value;
        rules.length.classList.toggle('valid', /.{8,}/.test(val));
        rules.uppercase.classList.toggle('valid', /[A-Z]/.test(val));
        rules.lowercase.classList.toggle('valid', /[a-z]/.test(val));
        rules.number.classList.toggle('valid', /[0-9]/.test(val));
        rules.special.classList.toggle('valid', /[@$!%*?&#]/.test(val));

        registerSubmit.disabled = !allRulesValid();
    });

    // ---------- Confirm password validation ----------
    confirmPassword.addEventListener('input', () => {
        if (confirmPassword.value !== registerPassword.value) {
            showError("Passwords do not match!");
        } else {
            hideError();
        }
        registerSubmit.disabled = !allRulesValid();
    });

    // ---------- Form submit check ----------
    registerForm.addEventListener('submit', (e) => {
        if (!allRulesValid()) {
            e.preventDefault();
            showError("Please fix password rules before submitting.");
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
