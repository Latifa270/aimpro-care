'use strict';

document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('unitSearch');
    const rows        = document.querySelectorAll('[data-unit-row]');
    const emptyState  = document.getElementById('unitSearchEmpty');

    if (searchInput && rows.length) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            let visible = 0;

            rows.forEach((row) => {
                const match = (row.dataset.search || '').includes(keyword);
                row.hidden = !match;
                if (match) visible++;
            });

            if (emptyState) {
                emptyState.hidden = visible !== 0;
            }
        });
    }

    const createModal = document.getElementById('addUnitModal');

    if (createModal) {
        const createForm = createModal.querySelector('form');

        createModal.addEventListener('hidden.bs.modal', function () {
            if (createForm) {
                createForm.reset();
            }
        });
    }

    const deleteModal = document.getElementById('deleteUnitModal');

    if (deleteModal) {
        const deleteForm = document.getElementById('deleteUnitForm');
        const deleteName = document.getElementById('deleteUnitName');
        const deleteMeta = document.getElementById('deleteUnitMeta');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const raw = button.getAttribute('data-unit');
            const action = button.getAttribute('data-action');

            if (!raw || !action) return;

            try {
                const unit = JSON.parse(raw);

                deleteForm.action = action;

                deleteName.textContent = unit.product_name || '-';
                deleteMeta.textContent = (unit.serial_number || '') + ' — ' + (unit.brand || '') + ' ' + (unit.model || '');

            } catch (err) {
                console.error('Gagal parse data unit:', err);
            }
        });

        deleteModal.addEventListener('hidden.bs.modal', function () {
            deleteForm.action = '';
            deleteName.textContent = '-';
            deleteMeta.textContent = '-';
        });
    }

});