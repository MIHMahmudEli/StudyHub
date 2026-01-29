// Floating Glass Circles Animation
function initCircleAnimation() {
  const container = document.getElementById('circle-container');
  if (!container) return;

  const circleCount = 10;
  const circles = [];

  for (let i = 0; i < circleCount; i++) {
    const circle = document.createElement('div');
    circle.className = 'glass-circle';

    const size = Math.random() * 150 + 50; // 50px to 200px
    const x = Math.random() * window.innerWidth;
    const y = Math.random() * window.innerHeight;

    // Random velocities (Slowed down for graceful movement)
    const vx = (Math.random() - 0.5) * 0.3;
    const vy = (Math.random() - 0.5) * 0.3;

    circle.style.width = `${size}px`;
    circle.style.height = `${size}px`;
    circle.style.left = `0px`;
    circle.style.top = `0px`;
    circle.style.transform = `translate(${x}px, ${y}px)`;

    container.appendChild(circle);
    circles.push({ el: circle, x, y, vx, vy, size });
  }

  function animate() {
    circles.forEach(c => {
      c.x += c.vx;
      c.y += c.vy;

      // Wrap around edges
      if (c.x < -c.size) c.x = window.innerWidth;
      if (c.x > window.innerWidth) c.x = -c.size;
      if (c.y < -c.size) c.y = window.innerHeight;
      if (c.y > window.innerHeight) c.y = -c.size;

      c.el.style.transform = `translate(${c.x}px, ${c.y}px)`;
    });
    requestAnimationFrame(animate);
  }

  animate();
}

// Initialize on load
document.addEventListener('DOMContentLoaded', initCircleAnimation);

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
