document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll("#ratingStars .star");
    const noteId = document.getElementById("ratingStars")?.dataset.noteId;
    const submitBtn = document.getElementById("submitRating");
    const msgBox = document.getElementById("ratingMsg");

    let selectedRating = document.querySelectorAll("#ratingStars .filled").length;

    // Hover + click behavior
    stars.forEach(star => {
        star.addEventListener("mouseover", () => {
            stars.forEach(s => s.classList.remove("hovered"));
            for (let i = 0; i < star.dataset.value; i++) stars[i].classList.add("hovered");
        });
        star.addEventListener("mouseout", () => {
            stars.forEach(s => s.classList.remove("hovered"));
        });
        star.addEventListener("click", () => {
            selectedRating = parseInt(star.dataset.value);
            stars.forEach(s => s.classList.remove("filled"));
            for (let i = 0; i < selectedRating; i++) stars[i].classList.add("filled");
        });
    });

    // Submit rating via AJAX
    if (submitBtn) {
        submitBtn.addEventListener("click", () => {
            if (selectedRating === 0) {
                msgBox.textContent = "⚠️ Please select a rating.";
                msgBox.style.color = "orange";
                return;
            }

            const comment = document.getElementById("ratingComment").value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "rate_note.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const res = xhr.responseText.trim();
                    if (res === "added") {
                        msgBox.textContent = "✅ Thank you! Rating added.";
                        msgBox.style.color = "green";
                    } else if (res === "updated") {
                        msgBox.textContent = "✅ Your rating was updated.";
                        msgBox.style.color = "green";
                    } else if (res === "invalid_rating") {
                        msgBox.textContent = "❌ Invalid rating.";
                        msgBox.style.color = "red";
                    } else if (res === "not_logged_in") {
                        msgBox.textContent = "⚠️ Please log in to rate.";
                        msgBox.style.color = "orange";
                    } else {
                        msgBox.textContent = "❌ Error: " + res;
                        msgBox.style.color = "red";
                    }
                }
            };
            xhr.send(
                "note_id=" + encodeURIComponent(noteId) +
                "&rating=" + encodeURIComponent(selectedRating) +
                "&comment=" + encodeURIComponent(comment)
            );
        });
    }
});
