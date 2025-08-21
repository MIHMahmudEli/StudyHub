document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById('file');
    const fileName = document.getElementById('file-name');
    const form = document.getElementById('upload-form');
    const maxSize = 40 * 1024 * 1024; // 40MB

    // Show selected file name
    fileInput.addEventListener('change', () => {
        if(fileInput.files.length > 0){
            fileName.textContent = fileInput.files[0].name;
        } else {
            fileName.textContent = "No file chosen";
        }
    });

    // Validate file size on submit
    form.addEventListener('submit', (e) => {
        if(fileInput.files.length > 0 && fileInput.files[0].size > maxSize){
            e.preventDefault();
            alert("File is too large! Maximum allowed size is 40MB.");
        }
    });
});
