// Footer Year
document.getElementById('year').textContent = new Date().getFullYear();

// Reveal Animation
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) entry.target.classList.add('active');
  });
}, { threshold: 0.2 });
reveals.forEach(r => observer.observe(r));

// Scroll to top
const topBtn = document.getElementById('topBtn');
window.addEventListener('scroll', () => {
  topBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
});
topBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });

// Counter Animation
const counters = document.querySelectorAll('.counter');
const speed = 200;
const counterObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      counters.forEach(counter => {
        const update = () => {
          const target = +counter.getAttribute('data-target');
          const count = +counter.innerText;
          const inc = target / speed;
          if (count < target) {
            counter.innerText = Math.ceil(count + inc);
            requestAnimationFrame(update);
          } else {
            counter.innerText = target;
          }
        };
        update();
      });
      counterObserver.disconnect();
    }
  });
}, { threshold: 0.5 });
counters.forEach(c => counterObserver.observe(c));
