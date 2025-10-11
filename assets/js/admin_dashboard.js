const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.querySelector('.menu-toggle');
const toggleIcon = toggleBtn.querySelector('i');

// Sidebar toggle logic
toggleBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  document.body.classList.toggle('no-scroll');
  
  // Toggle icon
  if (sidebar.classList.contains('open')) {
    toggleIcon.classList.replace('fa-bars', 'fa-times');
  } else {
    toggleIcon.classList.replace('fa-times', 'fa-bars');
  }
});

// Close sidebar if clicking outside on mobile
document.addEventListener('click', (e) => {
  if (
    window.innerWidth <= 992 &&
    sidebar.classList.contains('open') &&
    !sidebar.contains(e.target) &&
    !toggleBtn.contains(e.target)
  ) {
    sidebar.classList.remove('open');
    document.body.classList.remove('no-scroll');
    toggleIcon.classList.replace('fa-times', 'fa-bars');
  }
});
