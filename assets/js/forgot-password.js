document.addEventListener("DOMContentLoaded", function() {
    const step1 = document.getElementById("step1");
    const step2 = document.getElementById("step2");
    const resendCode = document.getElementById("resendCode");
    const cancelReset = document.getElementById("cancelReset");
    const passwordInput = document.getElementById("newPassword"); 
    const confirmPasswordInput = document.getElementById("confirmPassword"); 
    const formMessage = document.getElementById("formMessage"); 
    const resetForm = document.getElementById("step2"); 

    // Show Step 2 if redirected with hash
    if (window.location.hash === '#forgot-password-step2') {
        step1.classList.add("d-none");
        step2.classList.remove("d-none");
    } else {
        step1.classList.remove("d-none");
        step2.classList.add("d-none");
    }

    // Cancel button → back to login
    cancelReset.addEventListener("click", () => window.location.href = "main_index.php#login");

    // Resend OTP
    resendCode.addEventListener("click", () => {
        resendCode.disabled = true;
        const originalText = resendCode.textContent;
        resendCode.textContent = "Sending...";

        fetch("resend-otp.php")
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    formMessage.textContent = data.message;
                    formMessage.className = "text-success";
                } else {
                    formMessage.textContent = "Error: " + data.message;
                    formMessage.className = "text-danger";
                }
                formMessage.style.display = "block";
            })
            .catch(() => {
                formMessage.textContent = "Something went wrong. Try again.";
                formMessage.className = "text-danger";
                formMessage.style.display = "block";
            })
            .finally(() => {
                setTimeout(() => {
                    resendCode.disabled = false;
                    resendCode.textContent = originalText;
                }, 2000);
            });
    });

    // Password regex
    function validatePassword(password) {
        // at least 8 chars, one uppercase, one lowercase, one number, one special char
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        return regex.test(password);
    }

    // Password validation before submit
    resetForm.addEventListener("submit", function(e) {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        if (!validatePassword(password)) {
            e.preventDefault();
            formMessage.textContent = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
            formMessage.className = "text-danger";
            formMessage.style.display = "block";
            return;
        }

        if (password !== confirmPassword) {
            e.preventDefault();
            formMessage.textContent = "Passwords do not match!";
            formMessage.className = "text-danger";
            formMessage.style.display = "block";
            return;
        }

        // show success
        formMessage.textContent = "Password valid. Submitting...";
        formMessage.className = "text-success";
        formMessage.style.display = "block";
    });
});
