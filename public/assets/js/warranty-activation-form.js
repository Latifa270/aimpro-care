'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('waActivationForm');

    if (!form) {
        return;
    }

    const purchaseDateInput = document.getElementById('waPurchaseDate');
    const durationInput     = document.getElementById('waDuration');
    const previewStart      = document.getElementById('waPreviewStart');
    const previewEnd        = document.getElementById('waPreviewEnd');

    const formatter = new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });

    const parseLocalDate = (value) => {
        if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return null;
        }

        const [year, month, day] = value.split('-').map(Number);
        const date = new Date(year, month - 1, day);

        if (
            date.getFullYear() !== year ||
            date.getMonth() !== month - 1 ||
            date.getDate() !== day
        ) {
            return null;
        }

        return date;
    };

    const formatDate = (date) => formatter.format(date);

    const calculateEndDate = (startDate, durationMonths) => {
        const firstDay = new Date(
            startDate.getFullYear(),
            startDate.getMonth(),
            1
        );

        const targetMonth = new Date(
            firstDay.getFullYear(),
            firstDay.getMonth() + durationMonths,
            1
        );

        const lastDayOfTargetMonth = new Date(
            targetMonth.getFullYear(),
            targetMonth.getMonth() + 1,
            0
        ).getDate();

        const targetDay = Math.min(
            startDate.getDate(),
            lastDayOfTargetMonth
        );

        const endDate = new Date(
            targetMonth.getFullYear(),
            targetMonth.getMonth(),
            targetDay
        );

        endDate.setDate(endDate.getDate() - 1);

        return endDate;
    };

    const updateWarrantyPreview = () => {
        if (!purchaseDateInput || !durationInput) {
            return;
        }

        const startDate = parseLocalDate(purchaseDateInput.value);
        const duration  = Number.parseInt(durationInput.value, 10);

        if (
            !startDate ||
            !Number.isInteger(duration) ||
            duration < 1 ||
            duration > 120
        ) {
            if (previewStart) previewStart.textContent = '-';
            if (previewEnd) previewEnd.textContent = '-';
            return;
        }

        const endDate = calculateEndDate(startDate, duration);

        if (previewStart) previewStart.textContent = formatDate(startDate);
        if (previewEnd) previewEnd.textContent = formatDate(endDate);
    };

    if (purchaseDateInput) {
        purchaseDateInput.addEventListener('change', () => {
            purchaseDateInput.setCustomValidity('');
            updateWarrantyPreview();
        });
    }

    if (durationInput) {
        durationInput.addEventListener('input', () => {
            durationInput.setCustomValidity('');
            updateWarrantyPreview();
        });
    }

    updateWarrantyPreview();

    const receiptInput       = document.getElementById('waReceipt');
    const removeReceiptBtn   = document.getElementById('waRemoveReceipt');
    const filePreview        = document.getElementById('waFilePreview');
    const fileNamePreview    = document.getElementById('waFileNamePreview');
    const fileSize           = document.getElementById('waFileSize');

    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    const ALLOWED_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf'
    ];

    const formatFileSize = (bytes) => {
        if (bytes < 1024 * 1024) {
            return `${(bytes / 1024).toFixed(1)} KB`;
        }

        return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
    };

    const resetReceipt = () => {
        if (!receiptInput) return;

        receiptInput.value = '';

        if (filePreview) filePreview.hidden = true;
        if (fileNamePreview) fileNamePreview.textContent = '';
        if (fileSize) fileSize.textContent = '';
    };

    const displayReceipt = (file) => {
        if (!file) {
            resetReceipt();
            return;
        }

        if (file.size > MAX_FILE_SIZE) {
            alert('Ukuran file maksimal 5 MB.');
            resetReceipt();
            return;
        }

        if (ALLOWED_TYPES.length && !ALLOWED_TYPES.includes(file.type)) {
            alert('Format file harus JPG, PNG, WEBP, atau PDF.');
            resetReceipt();
            return;
        }

        if (fileNamePreview) fileNamePreview.textContent = file.name;
        if (fileSize) fileSize.textContent = formatFileSize(file.size);
        if (filePreview) filePreview.hidden = false;
    };

    if (receiptInput) {
        receiptInput.addEventListener('change', () => {
            const file = receiptInput.files[0];

            if (file) {
                displayReceipt(file);
            }
        });
    }

    if (removeReceiptBtn) {
        removeReceiptBtn.addEventListener('click', resetReceipt);
    }

    const submitButton = document.getElementById('waSubmitButton');

    if (submitButton) {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            const duration = Number.parseInt(durationInput.value, 10);

            if (
                !Number.isInteger(duration) ||
                duration < 1 ||
                duration > 120
            ) {
                event.preventDefault();
                durationInput.focus();
                durationInput.setCustomValidity(
                    'Durasi garansi harus antara 1 sampai 120 bulan.'
                );
                durationInput.reportValidity();
                durationInput.setCustomValidity('');
                return;
            }

            const purchaseDate = parseLocalDate(purchaseDateInput.value);

            if (!purchaseDate) {
                event.preventDefault();
                purchaseDateInput.focus();
                purchaseDateInput.setCustomValidity(
                    'Tanggal pembelian tidak valid.'
                );
                purchaseDateInput.reportValidity();
                purchaseDateInput.setCustomValidity('');
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span>Menyimpan...</span>
            `;
        });
    }
});