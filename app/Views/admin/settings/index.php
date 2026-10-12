<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$settings = $settings ?? [];

$get = static function (string $key, $default = '') use ($settings) {
    return $settings[$key] ?? $default;
};
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-gear"></i>
                    SISTEM
                </span>

                <h1 class="app-page-title">Pengaturan</h1>

                <p class="app-page-description">
                    Kelola pengaturan sistem AIMpro Care.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-gear"></i>
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

    <form action="<?= site_url('admin/settings') ?>" method="post">

        <?= csrf_field() ?>

        <div class="row g-4">

            <div class="col-12 col-lg-8">
                <div class="app-card mb-4">
                    <div class="app-card-header">
                        <div>
                            <h2 class="app-card-title">Identitas Perusahaan</h2>
                            <p class="app-card-subtitle mb-0">
                                Informasi perusahaan untuk kartu garansi.
                            </p>
                        </div>

                        <div class="app-stat-icon app-stat-icon-primary">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>

                    <div class="app-card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label for="company_name" class="form-label">
                                    Nama Perusahaan
                                </label>

                                <input
                                    type="text"
                                    id="company_name"
                                    name="company_name"
                                    class="form-control"
                                    value="<?= esc($get('company_name', 'AIMpro Care')) ?>"
                                    maxlength="100"
                                    placeholder="Contoh: AIMpro Care">
                            </div>

                            <div class="col-12">
                                <label for="company_address" class="form-label">
                                    Alamat Perusahaan
                                </label>

                                <textarea
                                    id="company_address"
                                    name="company_address"
                                    class="form-control"
                                    rows="3"
                                    maxlength="500"
                                    placeholder="Masukkan alamat perusahaan"><?= esc($get('company_address')) ?></textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="company_phone" class="form-label">
                                    Nomor Telepon
                                </label>

                                <input
                                    type="text"
                                    id="company_phone"
                                    name="company_phone"
                                    class="form-control"
                                    value="<?= esc($get('company_phone')) ?>"
                                    maxlength="20"
                                    placeholder="Contoh: 021-12345678">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="app-card">
                    <div class="app-card-header">
                        <div>
                            <h2 class="app-card-title">Pengaturan Sistem</h2>
                            <p class="app-card-subtitle mb-0">
                                Atur ukuran file & pengingat garansi.
                            </p>
                        </div>

                        <div class="app-stat-icon app-stat-icon-primary">
                            <i class="bi bi-sliders"></i>
                        </div>
                    </div>

                    <div class="app-card-body">
                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="max_file_size" class="form-label">
                                    Ukuran Maksimal File QC (KB) <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="max_file_size"
                                    name="max_file_size"
                                    class="form-control"
                                    value="<?= esc($get('max_file_size', 5120)) ?>"
                                    min="1"
                                    max="51200"
                                    required>

                                <div class="form-text">
                                    Maksimal 50.000 KB (50 MB).
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="warranty_reminder_days" class="form-label">
                                    Pengingat Garansi (Hari) <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="warranty_reminder_days"
                                    name="warranty_reminder_days"
                                    class="form-control"
                                    value="<?= esc($get('warranty_reminder_days', 30)) ?>"
                                    min="1"
                                    max="90"
                                    required>

                                <div class="form-text">
                                    Kirim pengingat X hari sebelum garansi berakhir.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-4">

                <div class="app-card">
                    <div class="app-card-header">
                        <div>
                            <h2 class="app-card-title">Aksi</h2>
                            <p class="app-card-subtitle mb-0">
                                Simpan atau batalkan perubahan.
                            </p>
                        </div>

                        <div class="app-stat-icon app-stat-icon-primary">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>

                    <div class="app-card-body">

                        <button type="submit" class="btn-app-primary w-100">
                            <i class="bi bi-check-lg"></i>
                            <span>Simpan Pengaturan</span>
                        </button>

                        <a href="<?= site_url('admin/dashboard') ?>" class="btn-app-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali</span>
                        </a>

                        <p class="text-muted small text-center mt-3 mb-0">
                            <i class="bi bi-info-circle"></i>
                            Pengaturan akan diterapkan ke seluruh sistem.
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<?= $this->endSection() ?>