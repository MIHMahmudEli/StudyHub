// Toggle action buttons for user management
function toggleActions(row) {
    let next = row.nextElementSibling;
    if (next && next.classList.contains("action-row")) {
        // Toggle
        next.style.display = (next.style.display === "table-row") ? "none" : "table-row";
    }
}

