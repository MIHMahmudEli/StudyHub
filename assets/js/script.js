document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');

    const registerPassword = document.getElementById('registerPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    
    // Correctly select the error message element
    const errorMessage = document.querySelector('#registerForm .error-message');

    // Check URL hash and show appropriate form
    if (window.location.hash === '#register') {
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
    }

    // Toggle forms
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
            
            // Clear error message when switching forms
            errorMessage.textContent = "";
            errorMessage.style.display = "none";
            
            // Update URL hash
            if (registerForm.classList.contains('active')) {
                window.location.hash = 'register';
            } else {
                window.location.hash = '';
            }
        });
    });

    // Toggle password visibility
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

    // Check if confirm password matches dynamically
    confirmPassword.addEventListener('input', () => {
        if (confirmPassword.value !== registerPassword.value) {
            errorMessage.textContent = "Passwords do not match!";
            errorMessage.style.display = "block";
        } else {
            errorMessage.textContent = "";
            errorMessage.style.display = "none";
        }
    });

    // Also check when the password field changes
    registerPassword.addEventListener('input', () => {
        if (confirmPassword.value && confirmPassword.value !== registerPassword.value) {
            errorMessage.textContent = "Passwords do not match!";
            errorMessage.style.display = "block";
        } else {
            errorMessage.textContent = "";
            errorMessage.style.display = "none";
        }
    });

    // Optional: prevent form submit if mismatch
    registerForm.addEventListener('submit', (e) => {
        if (confirmPassword.value !== registerPassword.value) {
            e.preventDefault();
            errorMessage.textContent = "Passwords do not match!";
            errorMessage.style.display = "block";
            
            // Add a little animation to draw attention to the error
            errorMessage.style.animation = "shake 0.5s";
            setTimeout(() => {
                errorMessage.style.animation = "";
            }, 500);
        }
    });
});