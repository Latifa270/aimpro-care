<div
    class="modal fade app-modal"
    id="addUnitModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form action="<?= site_url('admin/units') ?>" method="post">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="app-stat-icon app-stat-icon-primary">
                            <i class="bi bi-plus-circle"></i>
                        </div>

                        <div>
                            <h5 class="modal-title mb-1">
                                Tambah Unit
                            </h5>

                            <small class="app-modal-subtitle">
                                Tambahkan unit fisik dan serial number
                            </small>
                        </div>
                    </div>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="product_id" class="form-label">
                            Produk <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select"
                            id="product_id"
                            name="product_id"
                            required>

                            <option value="">
                                Pilih Produk
                            </option>

                            <?php foreach ($products as $product): ?>
                                <option
                                    value="<?= esc($product['id']) ?>"
                                    <?= old('product_id') == $product['id'] ? 'selected' : '' ?>>

                                    <?= esc($product['name']) ?>
                                    -
                                    <?= esc($product['brand']) ?>
                                    <?= esc($product['model']) ?>

                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="serial_number" class="form-label">
                            Serial Number <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="serial_number"
                            name="serial_number"
                            placeholder="Masukkan serial number unit"
                            value="<?= old('serial_number') ?>"
                            maxlength="100"
                            required>

                        <div class="form-text">
                            Serial number harus unik untuk setiap unit.
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn-app-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn-app-primary">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Unit</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>