<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-cloud-arrow-up"></i>
                    QUALITY CONTROL
                </span>

                <h1 class="app-page-title">Dokumentasi QC</h1>

                <p class="app-page-description">
                    Upload foto produk, foto nomor seri, dan video unboxing.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-cloud-arrow-up"></i>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="app-alert app-alert-success mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Periksa kembali data berikut:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="app-card mb-4">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Informasi Pemeriksaan</h2>
                <p class="app-card-subtitle mb-0">
                    Data unit dan petugas QC.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-info-circle"></i>
            </div>
        </div>

        <div class="app-card-body">
            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">PRODUK</span>
                    <div class="fw-semibold"><?= esc($inspection['product_name']) ?></div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">BRAND</span>
                    <div class="fw-semibold"><?= esc($inspection['brand']) ?></div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">MODEL</span>
                    <div class="fw-semibold"><?= esc($inspection['model']) ?></div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">SERIAL NUMBER</span>
                    <div class="fw-semibold"><?= esc($inspection['serial_number']) ?></div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">PETUGAS QC</span>
                    <div class="fw-semibold"><?= esc($inspection['qc_officer']) ?></div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">TANGGAL PEMERIKSAAN</span>
                    <div class="fw-semibold"><?= esc($inspection['inspection_date']) ?></div>
                </div>

            </div>
        </div>
    </div>

    <div class="app-card">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Upload Dokumentasi</h2>
                <p class="app-card-subtitle mb-0">
                    Semua dokumentasi wajib diupload sebelum melanjutkan.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-images"></i>
            </div>
        </div>

        <div class="app-card-body">
            <form
                action="<?= site_url('qc/inspections/' . $inspection['id'] . '/documentation') ?>"
                method="post"
                enctype="multipart/form-data">

                <?= csrf_field() ?>

                <div class="row g-4">

                    <div class="col-12 col-lg-4">
                        <div class="app-card h-100">
                            <div class="app-card-body text-center">
                                <div class="app-stat-icon app-stat-icon-primary mx-auto mb-3">
                                    <i class="bi bi-camera"></i>
                                </div>

                                <h3 class="app-card-title mb-1">Foto Produk</h3>

                                <p class="app-card-subtitle mb-3">
                                    Foto kondisi produk secara keseluruhan.
                                </p>

                                <label class="app-upload w-100" for="photo_product">
                                    <input
                                        type="file"
                                        name="photo_product"
                                        id="photo_product"
                                        accept="image/jpeg,image/png,image/webp"
                                        hidden
                                        required>

                                    <span class="app-upload-icon">
                                        <i class="bi bi-camera"></i>
                                    </span>

                                    <span class="app-upload-copy">
                                        <strong>Pilih Foto Produk</strong>
                                        <span>JPG, JPEG, PNG, WEBP — maksimal 5 MB.</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="app-card h-100">
                            <div class="app-card-body text-center">
                                <div class="app-stat-icon app-stat-icon-primary mx-auto mb-3">
                                    <i class="bi bi-upc-scan"></i>
                                </div>

                                <h3 class="app-card-title mb-1">Foto Nomor Seri</h3>

                                <p class="app-card-subtitle mb-3">
                                    Foto yang memperlihatkan nomor seri unit.
                                </p>

                                <label class="app-upload w-100" for="photo_serial">
                                    <input
                                        type="file"
                                        name="photo_serial"
                                        id="photo_serial"
                                        accept="image/jpeg,image/png,image/webp"
                                        hidden
                                        required>

                                    <span class="app-upload-icon">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>

                                    <span class="app-upload-copy">
                                        <strong>Pilih Foto Nomor Seri</strong>
                                        <span>JPG, JPEG, PNG, WEBP — maksimal 5 MB.</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="app-card h-100">
                            <div class="app-card-body text-center">
                                <div class="app-stat-icon app-stat-icon-primary mx-auto mb-3">
                                    <i class="bi bi-camera-video"></i>
                                </div>

                                <h3 class="app-card-title mb-1">Video Unboxing</h3>

                                <p class="app-card-subtitle mb-3">
                                    Video proses pembukaan dan pemeriksaan awal.
                                </p>

                                <label class="app-upload w-100" for="video_unboxing">
                                    <input
                                        type="file"
                                        name="video_unboxing"
                                        id="video_unboxing"
                                        accept="video/mp4,video/webm,video/quicktime"
                                        hidden>

                                    <span class="app-upload-icon">
                                        <i class="bi bi-camera-video"></i>
                                    </span>

                                    <span class="app-upload-copy">
                                        <strong>Pilih Video Unboxing</strong>
                                        <span>MP4, WEBM, MOV — maksimal 50 MB.</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex flex-column flex-sm-row gap-2 mt-4">

                    <a
                        href="<?= site_url('qc/inspections/' . $inspection['id'] . '/checklist') ?>"
                        class="btn-app-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>

                    <button
                        type="submit"
                        class="btn-app-primary">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Simpan Dokumentasi</span>
                    </button>

                </div>

            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>