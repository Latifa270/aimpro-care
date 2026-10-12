<div
    class="modal fade app-modal"
    id="editProductModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form action="" method="post" id="editProductForm">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Produk
                        </h5>
                        <small class="app-modal-subtitle">
                            Perbarui informasi master produk
                        </small>
                    </div>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input
                            type="text"
                            name="name"
                            id="edit_name"
                            class="form-control"
                            maxlength="150"
                            required>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Brand</label>
                            <input
                                type="text"
                                name="brand"
                                id="edit_brand"
                                class="form-control"
                                maxlength="100"
                                required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Model</label>
                            <input
                                type="text"
                                name="model"
                                id="edit_model"
                                class="form-control"
                                maxlength="100"
                                required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea
                            name="description"
                            id="edit_description"
                            rows="4"
                            class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn-app-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn-app-primary">
                        <i class="bi bi-check-circle"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>