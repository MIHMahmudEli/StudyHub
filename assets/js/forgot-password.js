document.addEventListener("DOMContentLoaded", function() {
    const step1 = document.getElementById("step1");
    const step2 = document.getElementById("step2");
    const backToLogin = document.getElementById("backToLogin");
    const cancelReset = document.getElementById("cancelReset");
    const resendCode = document.getElementById("resendCode");
    const passwordInput = document.getElementById("newPassword"); 
    const confirmPasswordInput = document.getElementById("confirmPassword"); 
    const formMessage = document.getElementById("formMessage"); 
    const resetForm = document.getElementById("step2"); 

    // Show Step 2 if redirected with hash
    if(window.location.hash === '#forgot-password-step2'){
        step1.classList.remove("active");
        step2.classList.add("active");
    } else {
        step1.classList.add("active");
        step2.classList.remove("active");
    }

    // Cancel buttons → back to login
    backToLogin.addEventListener("click", () => window.location.href = "index.php");
    cancelReset.addEventListener("click", () => window.location.href = "index.php");

    // Resend OTP
    resendCode.addEventListener("click", () => {
        resendCode.disabled = true;
        let originalText = resendCode.textContent;
        resendCode.textContent = "Sending...";

        fetch("resend-otp.php")
            .then(res => res.json())
            .then(data => {
                if(data.status === "success"){
                    formMessage.textContent = data.message;
                    formMessage.className = "form-message success-msg"; 
                } else {
                    formMessage.textContent = "Error: " + data.message;
                    formMessage.className = "form-message error-msg"; 
                }
                formMessage.style.display = "block";
            })
            .catch(() => {
                formMessage.textContent = "Something went wrong. Try again.";
                formMessage.className = "form-message error-msg";
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
    function validatePassword(password){
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;
        return regex.test(password);
    }

    // Password validation before submit
    resetForm.addEventListener("submit", function(e){
        e.preventDefault();
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        if(!validatePassword(password)){
            formMessage.textContent = "Password must be at least 6 characters, include uppercase, lowercase, number, and special character.";
            formMessage.className = "form-message error-msg"; 
            formMessage.style.display = "block";
            return;
        }

        if(password !== confirmPassword){
            formMessage.textContent = "Passwords do not match!";
            formMessage.className = "form-message error-msg";
            formMessage.style.display = "block";
            return;
        }

        // If valid
        formMessage.textContent = "Password valid. Submitting...";
        formMessage.className = "form-message success-msg";
        formMessage.style.display = "block";

        resetForm.submit();
    });
});
