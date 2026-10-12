<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-clipboard-check"></i>
                    QUALITY CONTROL
                </span>

                <h1 class="app-page-title">Pemeriksaan QC</h1>

                <p class="app-page-description">
                    Periksa kondisi dan kelengkapan unit sebelum menentukan hasil QC.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
        </div>
    </div>

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
                <h2 class="app-card-title">Data Unit</h2>
                <p class="app-card-subtitle mb-0">
                    Informasi unit yang akan diperiksa.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>

        <div class="app-card-body">
            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">PRODUK</span>
                    <div class="fw-semibold">
                        <?= esc($unit['product_name']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">BRAND</span>
                    <div class="fw-semibold">
                        <?= esc($unit['brand']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">MODEL</span>
                    <div class="fw-semibold">
                        <?= esc($unit['model']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">SERIAL NUMBER</span>
                    <div class="fw-semibold">
                        <?= esc($unit['serial_number']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">PETUGAS QC</span>
                    <div class="fw-semibold">
                        <?= esc(session()->get('name')) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">TANGGAL PEMERIKSAAN</span>
                    <div class="fw-semibold">
                        <?= date('d-m-Y H:i:s') ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="app-card">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Form Pemeriksaan</h2>
                <p class="app-card-subtitle mb-0">
                    Isi kondisi fisik dan kelengkapan unit.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-pencil-square"></i>
            </div>
        </div>

        <div class="app-card-body">
            <form
                action="<?= site_url('qc/inspections/' . $unit['id']) ?>"
                method="post">

                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="physical_condition" class="form-label">
                        Kondisi Fisik <span class="text-danger">*</span>
                    </label>

                    <textarea
                        class="form-control"
                        id="physical_condition"
                        name="physical_condition"
                        rows="4"
                        placeholder="Jelaskan kondisi fisik produk..."
                        required><?= old('physical_condition') ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="completeness" class="form-label">
                        Kelengkapan <span class="text-danger">*</span>
                    </label>

                    <textarea
                        class="form-control"
                        id="completeness"
                        name="completeness"
                        rows="4"
                        placeholder="Jelaskan kelengkapan produk..."
                        required><?= old('completeness') ?></textarea>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">

                    <a
                        href="<?= site_url('qc/inspections') ?>"
                        class="btn-app-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <span>Batal</span>
                    </a>

                    <button
                        type="submit"
                        class="btn-app-primary">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Pemeriksaan</span>
                    </button>

                </div>

            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>