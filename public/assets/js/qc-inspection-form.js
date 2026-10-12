document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const form = document.querySelector('form[action*="qc/inspections"]');

    if (!form) {
        return;
    }

    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            return;
        }

        const confirmed = window.confirm(
            'Simpan pemeriksaan QC ini dan lanjut ke checklist?'
        );

        if (!confirmed) {
            event.preventDefault();
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span>Menyimpan...</span>
            `;
        }
    });
});