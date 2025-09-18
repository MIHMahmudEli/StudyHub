// Toggle action buttons for user management
function toggleActions(row) {
    var next = row.nextElementSibling;
    if (next && next.className.indexOf("action-row") !== -1) {
        // Toggle
        if (next.style.display === "table-row") {
            next.style.display = "none";
        } else {
            next.style.display = "table-row";
        }
    }
}
