import './bootstrap';

const toggle = document.getElementById('nav-toggle');
const panel = document.getElementById('mobile-nav');

if (toggle && panel) {
    toggle.addEventListener('click', () => {
        const open = panel.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
        const icon = toggle.querySelector('.material-symbols-outlined');
        if (icon) {
            icon.textContent = open ? 'menu' : 'close';
        }
    });
}
