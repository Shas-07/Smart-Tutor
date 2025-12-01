// Mobile Menu Toggle Functionality
(function() {
    'use strict';
    
    function initMobileMenu() {
        // Create mobile menu toggle button
        const mobileToggle = document.createElement('button');
        mobileToggle.className = 'mobile-menu-toggle';
        mobileToggle.innerHTML = '☰';
        mobileToggle.setAttribute('aria-label', 'Toggle menu');
        document.body.appendChild(mobileToggle);
        
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'mobile-overlay';
        document.body.appendChild(overlay);
        
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) return;
        
        // Toggle menu function
        function toggleMenu() {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
            mobileToggle.innerHTML = sidebar.classList.contains('mobile-open') ? '✕' : '☰';
        }
        
        // Toggle on button click
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });
        
        // Close on overlay click
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            mobileToggle.innerHTML = '☰';
        });
        
        // Close on sidebar link click (mobile)
        if (window.innerWidth <= 768) {
            const sidebarLinks = sidebar.querySelectorAll('.sidebar-nav a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(() => {
                        sidebar.classList.remove('mobile-open');
                        overlay.classList.remove('active');
                        mobileToggle.innerHTML = '☰';
                    }, 300);
                });
            });
        }
        
        // Close on window resize (if resizing to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                mobileToggle.innerHTML = '☰';
            }
        });
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileMenu);
    } else {
        initMobileMenu();
    }
})();

