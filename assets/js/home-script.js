document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".bookmark-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const noteId = btn.dataset.id;

            fetch("bookmark.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "note_id=" + noteId
            })
            .then(res => res.text())
            .then(data => {
                if (data === "added") {
                    alert("Note bookmarked!");
                } else if (data === "removed") {
                    alert("Bookmark removed!");
                } else {
                    alert("Error: " + data);
                }
            });
        });
    });
});
