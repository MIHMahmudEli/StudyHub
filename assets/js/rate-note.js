document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-star');
    const submitBtn = document.getElementById('submitRating');
    const ratingMsg = document.getElementById('ratingMsg');
    
    if (!submitBtn) return; // Exit if button not found
    
    const noteId = submitBtn.getAttribute('data-note-id');
    
    // Validate noteId exists
    if (!noteId) {
        console.error('Note ID not found');
        showMessage('Error: Note ID missing. Please refresh the page.', 'error');
        submitBtn.disabled = true;
        return;
    }

    let selectedRating = 0;

    // Select stars
    stars.forEach(star => {
        star.addEventListener('click', () => {
            selectedRating = parseInt(star.getAttribute('data-value'));
            stars.forEach(s => {
                const val = parseInt(s.getAttribute('data-value'));
                s.classList.toggle('active', val <= selectedRating);
            });
        });
    });

    // Submit rating
    submitBtn.addEventListener('click', () => {
        if (selectedRating === 0) {
            showMessage('Please select a rating first.', 'warning');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        fetch('submit_rating.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `note_id=${encodeURIComponent(noteId)}&rating=${encodeURIComponent(selectedRating)}`
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                updateAverageRating(data.new_avg);
                // Reset stars after successful submission
                stars.forEach(s => s.classList.remove('active'));
                selectedRating = 0;
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showMessage('Network error. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-star me-2"></i>Submit Rating';
        });
    });

    function updateAverageRating(newAvg) {
        const avgEl = document.querySelector('.avg-rating');
        if (avgEl) avgEl.textContent = parseFloat(newAvg).toFixed(1);

        const avgStars = document.querySelectorAll('.rating-display .star');
        avgStars.forEach((star, idx) => {
            const starValue = idx + 1;
            star.classList.toggle('filled', starValue <= Math.round(newAvg));
        });
    }

    function showMessage(msg, type) {
        if (!ratingMsg) return;
        
        ratingMsg.textContent = msg;
        ratingMsg.className = `rating-message alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} mt-2`;
        ratingMsg.style.display = 'block';
        
        setTimeout(() => {
            ratingMsg.style.display = 'none';
            ratingMsg.className = 'rating-message';
        }, 4000);
    }
});