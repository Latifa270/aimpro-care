<div
    class="modal fade app-modal"
    id="addProductModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form action="<?= site_url('admin/products') ?>" method="post">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Produk
                        </h5>
                        <small class="app-modal-subtitle">
                            Lengkapi informasi master produk
                        </small>
                    </div>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?= old('name') ?>"
                            placeholder="Contoh: Kulkas, Televisi, Mesin cuci"
                            maxlength="150"
                            required>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Brand</label>
                            <input
                                type="text"
                                name="brand"
                                class="form-control"
                                value="<?= old('brand') ?>"
                                placeholder="Contoh: Samsung, LG, Sharp"
                                maxlength="100"
                                required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Model</label>
                            <input
                                type="text"
                                name="model"
                                class="form-control"
                                value="<?= old('model') ?>"
                                placeholder="Contoh: RT-42, KX-100"
                                maxlength="100"
                                required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            placeholder="Masukkan deskripsi produk"><?= old('description') ?></textarea>
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
                        <span>Simpan Produk</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>