document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleBtn');
    const mobileBtn = document.getElementById('mobileBtn');
    const sidebar = document.getElementById('sidebar');
    const topbar = document.getElementById('topbar');
    const content = document.getElementById('content');
    const overlay = document.getElementById('overlay');

    // Desktop toggle
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            topbar.classList.toggle('full');
            content.classList.toggle('full');
        });
    }

    // Mobile menu
    if (mobileBtn) {
        mobileBtn.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        });
    }

    // Close mobile sidebar on overlay click
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-show');
            overlay.classList.remove('show');
        });
    }

    // Auto-highlight active nav link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});
