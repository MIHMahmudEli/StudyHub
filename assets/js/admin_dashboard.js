        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.menu-toggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });