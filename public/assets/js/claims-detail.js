document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const statusForm = document.querySelector('form[action*="/status"]');

    if (statusForm) {
        statusForm.addEventListener('submit', (event) => {
            if (!statusForm.checkValidity()) {
                return;
            }

            const statusSelect = statusForm.querySelector('select[name="status"]');
            const selectedStatus = statusSelect ? statusSelect.value : '';

            const confirmed = window.confirm(
                `Perbarui status klaim menjadi "${selectedStatus}"?`
            );

            if (!confirmed) {
                event.preventDefault();
                return;
            }

            const submitButton = statusForm.querySelector('button[type="submit"]');

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span>Memproses...</span>
                `;
            }
        });
    }

    document.querySelectorAll('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".webp"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');

            if (!href) return;

            const confirmed = window.confirm('Buka foto kerusakan di tab baru?');

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});