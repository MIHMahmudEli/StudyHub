const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.querySelector('.menu-toggle');
const toggleIcon = toggleBtn.querySelector('i');

// Create and append overlay
const overlay = document.createElement('div');
overlay.className = 'sidebar-overlay';
document.body.appendChild(overlay);

function toggleSidebar(forceClose = false) {
  const isOpen = forceClose ? false : !sidebar.classList.contains('open');

  if (isOpen) {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    toggleIcon.classList.replace('fa-bars', 'fa-times');
  } else {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    toggleIcon.classList.replace('fa-times', 'fa-bars');
  }

  // Resynchronize charts if they exist
  setTimeout(syncAdminCharts, 300);
}

// Sidebar toggle logic
toggleBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  toggleSidebar();
});

// Close sidebar if clicking on overlay
overlay.addEventListener('click', () => {
  toggleSidebar(true);
});

// Accordion Behavior for Sidebar
const collapseElements = document.querySelectorAll('.collapse');
collapseElements.forEach(collapse => {
  collapse.addEventListener('show.bs.collapse', () => {
    // Close all other collapses
    collapseElements.forEach(other => {
      if (other !== collapse) {
        const bsCollapse = bootstrap.Collapse.getInstance(other);
        if (bsCollapse) {
          bsCollapse.hide();
        }
      }
    });
  });
});

// Resynchronize Chart.js instances on window resize
let resizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(syncAdminCharts, 200);

  // Close sidebar on resize if window becomes large
  if (window.innerWidth > 992 && sidebar.classList.contains('open')) {
    toggleSidebar(true);
  }
});

function syncAdminCharts() {
  if (window.adminCharts && Array.isArray(window.adminCharts)) {
    window.adminCharts.forEach(chart => {
      if (chart && typeof chart.resize === 'function') {
        chart.resize();
      }
    });
  }
}
