// Toggle Promote/Delete buttons on desktop
document.querySelectorAll('.user-row').forEach(row => {
    row.addEventListener('click', function() {
        const next = this.nextElementSibling;
        if (next && next.classList.contains('action-row')) {
            next.classList.toggle('d-none');
        }
    });
});
