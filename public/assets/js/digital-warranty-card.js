document.addEventListener('DOMContentLoaded', () => {
    const printButton = document.getElementById('dwcPrintButton');

    if (printButton) {
        printButton.addEventListener('click', () => {
            window.print();
        });
    }

    const generateForms = document.querySelectorAll(
        '.dwc-inline-form'
    );

    generateForms.forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!button) {
                return;
            }

            button.disabled = true;
            const originalHtml = button.innerHTML;
            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm"
                    aria-hidden="true"
                ></span>
                <span>Memproses...</span>
            `;

            window.setTimeout(() => {
                if (document.body.contains(button)) {
                    button.innerHTML = originalHtml;
                }
            }, 10000);
        });
    });
});