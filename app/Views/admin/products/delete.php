<div
    class="modal fade app-modal"
    id="deleteProductModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="" method="post" id="deleteProductForm">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="app-stat-icon app-stat-icon-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <div>
                            <h5 class="modal-title mb-1">
                                Hapus Produk
                            </h5>

                            <small class="app-modal-subtitle">
                                Tindakan ini tidak dapat dibatalkan
                            </small>
                        </div>
                    </div>
                </div>

                <div class="modal-body">

                    <p class="mb-3">
                        Apakah Anda yakin ingin menghapus produk berikut?
                    </p>

                    <div class="app-serial-block">
                        <span class="app-unit-label">PRODUK</span>

                        <div class="fw-semibold" id="deleteProductName">
                            -
                        </div>

                        <small class="text-muted" id="deleteProductMeta">
                            -
                        </small>
                    </div>

                    <div class="app-alert app-alert-warning mt-3">
                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Produk yang dihapus tidak dapat dikembalikan.
                            Pastikan tidak ada unit yang terkait.
                        </span>
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
                        class="btn-app-danger">
                        <i class="bi bi-trash"></i>
                        <span>Ya, Hapus</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>