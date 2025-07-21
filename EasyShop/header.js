window.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('all-toggle');
    const categories = document.getElementById('all-categories');

    if (toggle && categories) {
        toggle.addEventListener('click', () => {
            categories.classList.toggle('hidden');
        });
    }
});
