document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const sidebar = document.getElementById('appSidebar');
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileNavItems = document.querySelectorAll('.mobile-nav-item');

    if (!sidebar) {
        return;
    }

    // sidebar mobile 
    const openSidebar = () => {
        sidebar.classList.add('is-open');
        document.body.classList.add('sidebar-open');
    };

    const closeSidebar = () => {
        sidebar.classList.remove('is-open');
        document.body.classList.remove('sidebar-open');
    };

    const toggleSidebar = () => {
        if (sidebar.classList.contains('is-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    };

    // menu buttom mobile   
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            toggleSidebar();
        });
    }

    document.addEventListener('click', (event) => {

        const isMobile = window.innerWidth <= 991.98;

        if (!isMobile) {
            return;
        }

        if (!sidebar.classList.contains('is-open')) {
            return;
        }

        const clickedInsideSidebar = sidebar.contains(event.target);
        const clickedMenuButton = mobileMenuButton
            ? mobileMenuButton.contains(event.target)
            : false;

        if (!clickedInsideSidebar && !clickedMenuButton) {
            closeSidebar();
        }
    });

    document.addEventListener('keydown', (event) => {

        if (event.key !== 'Escape') {
            return;
        }

        if (sidebar.classList.contains('is-open')) {
            closeSidebar();
        }
    });

    mobileNavItems.forEach((item) => {

        item.addEventListener('click', () => {

            if (window.innerWidth <= 991.98) {
                closeSidebar();
            }

        });

    });

    window.addEventListener('resize', () => {

        if (window.innerWidth > 991.98) {
            closeSidebar();
        }

    });

});