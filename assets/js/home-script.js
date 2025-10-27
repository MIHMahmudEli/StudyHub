document.addEventListener('DOMContentLoaded', function() {
    // ====================== Bookmark Functionality ======================
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');
    
    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const noteId = this.getAttribute('data-id');
            toggleBookmark(noteId, this);
        });
    });

    function toggleBookmark(noteId, buttonElement) {
        const formData = new FormData();
        formData.append('note_id', noteId);

        fetch('bookmark', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'added') {
                if (!window.location.href.includes('bookmarks=1')) {
                    buttonElement.style.display = 'none';
                } else {
                    buttonElement.textContent = 'Remove Bookmark';
                    buttonElement.classList.remove('btn-primary');
                    buttonElement.classList.add('btn-danger');
                }
                showNotification('Bookmark added!', 'success');
            } else if (data === 'removed') {
                if (window.location.href.includes('bookmarks=1')) {
                    buttonElement.closest('.col').remove();
                    showNotification('Bookmark removed!', 'info');
                    const remainingCards = document.querySelectorAll('.col');
                    if (remainingCards.length === 0) {
                        document.querySelector('main .container').innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="fa fa-bookmark fa-3x text-muted mb-3"></i>
                                <p class="text-muted fs-5">No bookmarks found. Bookmark some notes to see them here!</p>
                                <a href="home" class="btn btn-primary">Browse Notes</a>
                            </div>
                        `;
                    }
                } else {
                    buttonElement.style.display = 'none';
                    showNotification('Bookmark removed!', 'info');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating bookmark', 'error');
        });
    }

    function showNotification(message, type) {
        const existingNotification = document.querySelector('.bookmark-notification');
        if (existingNotification) existingNotification.remove();

        const notification = document.createElement('div');
        notification.className = `bookmark-notification alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // ====================== Card Hover Effect ======================
    const noteCards = document.querySelectorAll('.note-card');
    noteCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // ====================== Skeleton Loader (Initial + Smooth Fade) ======================
    const skeleton = document.getElementById('skeleton-loader');
    const results = document.getElementById('note-results');

    if (skeleton && results) {
        // Initial page load skeleton
        setTimeout(() => {
            skeleton.classList.add('fade-out');
            setTimeout(() => {
                skeleton.style.display = 'none';
                results.style.display = 'block';
                results.style.opacity = '0';
                results.style.transition = 'opacity 0.6s ease';
                requestAnimationFrame(() => results.style.opacity = '1');
            }, 600);
        }, 800);
    }

    // ====================== Search Skeleton (AJAX-like UX) ======================
    const searchForm = document.querySelector('form[action*="home.php"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            // Before submitting, show skeleton again
            if (skeleton && results) {
                results.style.display = 'none';
                skeleton.style.display = 'flex';
                skeleton.classList.remove('fade-out');
                skeleton.style.opacity = '1';
            }
        });
    }
});
