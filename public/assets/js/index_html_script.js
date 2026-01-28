// Navbar scroll behavior
window.addEventListener('scroll', () => {
  const navbar = document.querySelector('.custom-navbar');
  if (window.scrollY > 50) {
    navbar.style.padding = '0.75rem 0';
    navbar.style.background = 'rgba(7, 10, 19, 0.98)';
    navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.5)';
  } else {
    navbar.style.padding = '1.25rem 0';
    navbar.style.background = 'rgba(7, 10, 19, 0.85)';
    navbar.style.boxShadow = 'none';
  }
});

// Reveal animations on scroll
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('active');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.fade-up').forEach(el => {
  revealObserver.observe(el);
});

// Scroll to top button
const topBtn = document.getElementById('topBtn');
window.addEventListener('scroll', () => {
  if (window.scrollY > 400) {
    topBtn.style.display = 'block';
  } else {
    topBtn.style.display = 'none';
  }
});

topBtn.onclick = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Smooth section scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const targetId = this.getAttribute('href');
    if (targetId === '#') return;

    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      window.scrollTo({
        top: targetElement.offsetTop - 80,
        behavior: 'smooth'
      });
    }
  });
});
