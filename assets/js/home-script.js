document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".bookmark-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const noteId = btn.dataset.id;

            // Create AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "bookmark.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const data = xhr.responseText.trim();

                    if (data === "added") {
                        alert("Note bookmarked!");
                        // optional: update button text/icon
                        btn.textContent = "Remove Bookmark";
                    } else if (data === "removed") {
                        alert("Bookmark removed!");
                        btn.textContent = "Bookmark";
                    } else {
                        alert("Error: " + data);
                    }
                }
            };

            xhr.send("note_id=" + encodeURIComponent(noteId));
        });
    });
});
