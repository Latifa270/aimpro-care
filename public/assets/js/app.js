document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const sidebar = document.getElementById('appSidebar');
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const sidebarCloseButton = document.getElementById('sidebarCloseButton');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (!sidebar) {
        return;
    }

    const isMobile = () => window.innerWidth <= 991.98;

    const openSidebar = () => {
        sidebar.classList.add('is-open');
        document.body.classList.add('sidebar-open');

        if (mobileMenuButton) {
            mobileMenuButton.setAttribute('aria-expanded', 'true');
        }
    };

    const closeSidebar = () => {
        sidebar.classList.remove('is-open');
        document.body.classList.remove('sidebar-open');

        if (mobileMenuButton) {
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    };

    const toggleSidebar = () => {
        if (sidebar.classList.contains('is-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    };

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleSidebar();
        });
    }

    if (sidebarCloseButton) {
        sidebarCloseButton.addEventListener('click', () => {
            closeSidebar();
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            closeSidebar();
        });
    }

    sidebar.querySelectorAll('a.sidebar-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (isMobile()) {
                closeSidebar();
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && sidebar.classList.contains('is-open')) {
            closeSidebar();
        }
    });

    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeSidebar();
        }
    });
});