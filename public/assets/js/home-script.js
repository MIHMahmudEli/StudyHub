document.addEventListener('DOMContentLoaded', function () {
    // ====================== Bookmark Functionality ======================
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');

    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const noteId = this.getAttribute('data-id');
            toggleBookmark(noteId, this);
        });
    });

    function toggleBookmark(id, buttonElement) {
        const formData = new FormData();
        const type = buttonElement.getAttribute('data-type') || 'note';
        const url = buttonElement.getAttribute('data-url') || 'home/bookmark';

        if (type === 'resource') {
            formData.append('resource_id', id);
        } else if (type === 'subject') {
            formData.append('subject_name', id);
        } else {
            formData.append('note_id', id);
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => response.text())
            .then(data => {
                if (data === 'added') {
                    if (!window.location.href.includes('bookmarks=1')) {
                        if (type === 'subject') {
                            // If user requested to hide icon when bookmarked:
                            buttonElement.remove();
                        } else {
                            // buttonElement.style.display = 'none'; // Don't hide, maybe just toggle style? User asked for icon.
                            // Actually for dashboard non-bookmark view, notes disappear if we don't want to show "remove".
                            // But in resource list, we want to toggle icon.
                            // For now, let's just toggle class if available.
                            buttonElement.innerHTML = type === 'resource' ? '<i class="fa fa-bookmark"></i>' : 'Remove Bookmark';
                            buttonElement.classList.add('btn-danger');
                            buttonElement.classList.remove('btn-primary', 'btn-outline-primary');
                        }
                    } else {
                        // In dashboard
                        // buttonElement.textContent = 'Remove Bookmark'; 
                        // Dashboard keeps trash icon or whatever layout is there
                        buttonElement.classList.remove('btn-primary');
                        buttonElement.classList.add('btn-danger');
                    }
                    showNotification('Bookmark added!', 'success');
                } else if (data === 'removed') {
                    if (window.location.href.includes('bookmarks=1')) {
                        // Remove the card from the view
                        buttonElement.closest('.col').remove();
                        showNotification('Bookmark removed!', 'info');

                        // Check if current pane is empty
                        // We need to know which pane we are in? The 'col' is inside a 'row'.
                        // Let's check visible cards.
                        const remainingCards = document.querySelectorAll('.tab-pane.active .col');
                        if (remainingCards.length === 0) {
                            // Ideally show "No bookmarks" message
                            // But implementation details vary.
                            location.reload(); // Simple fallback to refresh empty state
                        }
                    } else {
                        if (type === 'subject') {
                            // Subject index page
                            buttonElement.classList.remove('text-danger', 'bookmarked');
                            buttonElement.classList.add('text-muted');
                        } else {
                            // Standard toggle back
                            buttonElement.classList.remove('btn-danger');
                            buttonElement.classList.add('btn-primary'); // or outline
                            buttonElement.innerHTML = type === 'resource' ? '<i class="fa fa-bookmark"></i>' : '🔖 Bookmark';
                        }
                        showNotification('Bookmark removed!', 'info');
                    }
                } else {
                    console.error('Unknown response:', data);
                    showNotification('Error: ' + data, 'error');
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
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        card.addEventListener('mouseleave', function () {
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
        searchForm.addEventListener('submit', function () {
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

document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});


