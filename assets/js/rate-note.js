document.addEventListener("DOMContentLoaded", function() {
    const stars = document.querySelectorAll("#ratingStars .star");
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener("mouseover", () => {
            highlightStars(star.dataset.value);
        });
        star.addEventListener("mouseout", () => {
            highlightStars(selectedRating);
        });
        star.addEventListener("click", () => {
            selectedRating = star.dataset.value;
            submitRating(selectedRating);
        });
    });

    function highlightStars(rating) {
        stars.forEach(star => {
            if (star.dataset.value <= rating) {
                star.classList.add("filled");
            } else {
                star.classList.remove("filled");
            }
        });
    }

    function submitRating(rating) {
        const noteId = document.getElementById("ratingStars").dataset.noteId;
        fetch("submit_rating.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `note_id=${noteId}&rating=${rating}`
        })
        .then(res => res.json())
        .then(data => {
            const msg = document.getElementById("ratingMsg");
            if (data.status === "success") {
                msg.textContent = data.msg;
                msg.classList.remove("text-danger");
                msg.classList.add("text-success");
            } else {
                msg.textContent = data.msg;
                msg.classList.remove("text-success");
                msg.classList.add("text-danger");
            }
        })
        .catch(err => console.error(err));
    }
});
