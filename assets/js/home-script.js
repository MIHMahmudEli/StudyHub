document.addEventListener('DOMContentLoaded', function() {
    // Bookmark functionality
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
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'added') {
                // Bookmark was added - hide the button on home page
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
                    // If on bookmarks page, remove the entire card
                    buttonElement.closest('.col').remove();
                    showNotification('Bookmark removed!', 'info');
                    
                    // Check if no bookmarks left
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
                    // On home page - button should already be hidden, but just in case
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
        // Remove existing notifications
        const existingNotification = document.querySelector('.bookmark-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

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

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Note card hover effects
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
});