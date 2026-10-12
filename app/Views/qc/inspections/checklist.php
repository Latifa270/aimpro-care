<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-list-check"></i>
                    QUALITY CONTROL
                </span>

                <h1 class="app-page-title">Checklist QC</h1>

                <p class="app-page-description">
                    Periksa seluruh item sebelum menentukan hasil QC.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-list-check"></i>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <div class="app-card mb-4">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Informasi Pemeriksaan</h2>
                <p class="app-card-subtitle mb-0">
                    Data unit yang sedang diperiksa.
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
                    <div class="fw-semibold">
                        <?= esc($inspection['product_name']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">SERIAL NUMBER</span>
                    <div class="fw-semibold">
                        <?= esc($inspection['serial_number']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">PETUGAS QC</span>
                    <div class="fw-semibold">
                        <?= esc($inspection['qc_officer']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">TANGGAL PEMERIKSAAN</span>
                    <div class="fw-semibold">
                        <?= esc($inspection['inspection_date']) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="app-card">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">7 Checklist Pemeriksaan</h2>
                <p class="app-card-subtitle mb-0">
                    Pilih "Ya" atau "Tidak" untuk setiap item.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-check2-square"></i>
            </div>
        </div>

        <div class="app-card-body">
            <form
                action="<?= site_url('qc/inspections/' . $inspection['id'] . '/checklist') ?>"
                method="post">

                <?= csrf_field() ?>

                <?php
                $checklistItems = [
                    1 => 'Kemasan dalam kondisi baik',
                    2 => 'Produk tidak lecet atau retak',
                    3 => 'Nomor seri produk sesuai dengan kemasan',
                    4 => 'Kelengkapan produk sesuai',
                    5 => 'Produk dapat dinyalakan',
                    6 => 'Fungsi dasar berjalan',
                    7 => 'Tidak ditemukan kerusakan',
                ];
                ?>

                <?php foreach ($checklistItems as $key => $item): ?>
                    <div class="app-card mb-3">
                        <div class="app-card-body">
                            <div class="fw-semibold mb-3">
                                <?= $key ?>. <?= esc($item) ?>
                            </div>

                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="checklist[<?= $key ?>]"
                                        id="check_<?= $key ?>_yes"
                                        value="1"
                                        <?= old("checklist.$key") === '1' ? 'checked' : '' ?>
                                        required>

                                    <label class="form-check-label" for="check_<?= $key ?>_yes">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="checklist[<?= $key ?>]"
                                        id="check_<?= $key ?>_no"
                                        value="0"
                                        <?= old("checklist.$key") === '0' ? 'checked' : '' ?>>

                                    <label class="form-check-label" for="check_<?= $key ?>_no">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex flex-column flex-sm-row gap-2 mt-4">

                    <a
                        href="<?= site_url('qc/inspections') ?>"
                        class="btn-app-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <span>Batal</span>
                    </a>

                    <button
                        type="submit"
                        class="btn-app-primary">
                        <span>Lanjut ke Dokumentasi QC</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </div>

            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>