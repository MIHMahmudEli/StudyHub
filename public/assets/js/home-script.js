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
                            // Hide the bookmark button completely on main dashboard
                            buttonElement.style.display = 'none';
                        }
                    } else {
                        // In dashboard bookmarks view
                        buttonElement.classList.remove('btn-primary');
                        buttonElement.classList.add('btn-danger');
                    }
                    showToastNotification('Bookmark added successfully!', 'success');
                } else if (data === 'removed') {
                    if (window.location.href.includes('bookmarks=1')) {
                        // Remove the card from the view
                        buttonElement.closest('.col').remove();
                        showToastNotification('Bookmark removed!', 'info');

                        // Check if current pane is empty
                        const remainingCards = document.querySelectorAll('.tab-pane.active .col');
                        if (remainingCards.length === 0) {
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
                            buttonElement.classList.add('btn-primary');
                            buttonElement.innerHTML = type === 'resource' ? '<i class="fa fa-bookmark"></i>' : '🔖 Bookmark';
                        }
                        showToastNotification('Bookmark removed!', 'info');
                    }
                } else {
                    console.error('Unknown response:', data);
                    showToastNotification('Error: ' + data, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToastNotification('Error updating bookmark', 'error');
            });
    }

    function showToastNotification(message, type) {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-alert');
        if (existingToast) existingToast.remove();

        // Define colors and icons based on type
        const config = {
            success: {
                icon: 'fa-check-circle',
                gradient: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                color: '#065f46'
            },
            info: {
                icon: 'fa-info-circle',
                gradient: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                color: '#1e40af'
            },
            error: {
                icon: 'fa-exclamation-circle',
                gradient: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                color: '#991b1b'
            }
        };

        const typeConfig = config[type] || config.info;

        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'toast-alert';
        toast.innerHTML = `
            <div class="toast-alert-content">
                <div class="toast-alert-icon" style="background: ${typeConfig.gradient}">
                    <i class="fas ${typeConfig.icon}"></i>
                </div>
                <div class="toast-alert-message" style="color: ${typeConfig.color}">
                    ${message}
                </div>
                <button class="toast-alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-alert-progress" style="background: ${typeConfig.gradient}"></div>
        `;

        document.body.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('toast-show'), 10);

        // Auto dismiss after 4 seconds
        const dismissTimer = setTimeout(() => {
            toast.classList.remove('toast-show');
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 400);
        }, 4000);

        // Clear timer if manually closed
        toast.querySelector('.toast-alert-close').addEventListener('click', () => {
            clearTimeout(dismissTimer);
            toast.classList.remove('toast-show');
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 400);
        });
    }

    function showNotification(message, type) {
        // Deprecated - kept for backwards compatibility
        showToastNotification(message, type);
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


