// Ensure sidebar stays visible throughout navigation
(function() {
    'use strict';
    
    // Ensure sidebar is always visible
    function ensureSidebarVisible() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.display = 'block';
            sidebar.style.visibility = 'visible';
            sidebar.style.opacity = '1';
            sidebar.style.position = 'fixed';
            sidebar.style.top = '0';
            sidebar.style.left = '0';
            sidebar.style.zIndex = '1000';
        }
    }
    
    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', ensureSidebarVisible);
    } else {
        ensureSidebarVisible();
    }
    
    // Run after navigation (for SPA-like behavior)
    window.addEventListener('load', ensureSidebarVisible);
    
    // Monitor for any changes
    const observer = new MutationObserver(function(mutations) {
        ensureSidebarVisible();
    });
    
    // Observe sidebar changes
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }
})();

