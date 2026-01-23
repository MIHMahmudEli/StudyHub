// ---------- Password validation ----------
function validatePassword(password) {
    if (!/.{8,}/.test(password)) return "Password must be at least 8 characters long.";
    if (!/[A-Z]/.test(password)) return "Password must contain at least 1 uppercase letter.";
    if (!/[a-z]/.test(password)) return "Password must contain at least 1 lowercase letter.";
    if (!/[0-9]/.test(password)) return "Password must contain at least 1 number.";
    if (!/[@$!%*?&#]/.test(password)) return "Password must contain at least 1 special character (@$!%*?&#).";
    return "";
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form[name='password_form']");
    if (form) {
        form.addEventListener("submit", function (e) {
            const newPass = document.querySelector("input[name='new_password']").value;
            const confirmPass = document.querySelector("input[name='confirm_password']").value;
            const error = validatePassword(newPass);

            if (error) {
                e.preventDefault();
                alert(error);
                return false;
            }

            if (newPass !== confirmPass) {
                e.preventDefault();
                alert("Passwords do not match.");
                return false;
            }
        });
    }
});
