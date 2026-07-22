import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const revealElements = document.querySelectorAll('[data-reveal]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    revealElements.forEach((el) => observer.observe(el));

    // Re-render Lucide icons after Alpine mutates the DOM
    if (window.lucide) {
        window.lucide.createIcons();
    }
    
    document.addEventListener('alpine:updated', () => {
        if (window.lucide) window.lucide.createIcons();
    });
});