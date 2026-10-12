'use strict';

document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('productSearch');
    const rows        = document.querySelectorAll('[data-product-row]');
    const emptyState  = document.getElementById('productSearchEmpty');

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

    const createModal = document.getElementById('addProductModal');

    if (createModal) {
        const createForm = createModal.querySelector('form');

        createModal.addEventListener('hidden.bs.modal', function () {
            if (createForm) {
                createForm.reset();
            }
        });
    }

    const editModal = document.getElementById('editProductModal');

    if (editModal) {
        const editForm = document.getElementById('editProductForm');

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const raw = button.getAttribute('data-product');
            if (!raw) return;

            try {
                const product = JSON.parse(raw);

                editForm.action = button.dataset.action || '';

                document.getElementById('edit_name').value        = product.name || '';
                document.getElementById('edit_brand').value       = product.brand || '';
                document.getElementById('edit_model').value       = product.model || '';
                document.getElementById('edit_description').value = product.description || '';

            } catch (err) {
                console.error('Gagal parse data produk:', err);
            }
        });

        editModal.addEventListener('hidden.bs.modal', function () {
            editForm.reset();
            editForm.action = '';
        });
    }

    const deleteModal = document.getElementById('deleteProductModal');

    if (deleteModal) {
        const deleteForm = document.getElementById('deleteProductForm');
        const deleteName = document.getElementById('deleteProductName');
        const deleteMeta = document.getElementById('deleteProductMeta');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const raw = button.getAttribute('data-product');
            const action = button.getAttribute('data-action');

            if (!raw || !action) return;

            try {
                const product = JSON.parse(raw);

                deleteForm.action = action;

                deleteName.textContent = product.name || '-';
                deleteMeta.textContent = (product.brand || '') + ' ' + (product.model || '');

            } catch (err) {
                console.error('Gagal parse data produk:', err);
            }
        });

        deleteModal.addEventListener('hidden.bs.modal', function () {
            deleteForm.action = '';
            deleteName.textContent = '-';
            deleteMeta.textContent = '-';
        });
    }

});