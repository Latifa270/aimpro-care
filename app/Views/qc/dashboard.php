<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-semibold mb-1">
            Selamat datang, <?= esc(session()->get('name')) ?>
        </h2>

        <p class="text-secondary mb-0">
            Pantau aktivitas Quality Control hari ini.
        </p>
    </div>

    <!-- Summary -->
    <div class="row g-3">

        <!-- Menunggu QC -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-summary-card">

                <div class="dashboard-summary-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div class="dashboard-summary-label">
                    Menunggu QC
                </div>

                <div class="dashboard-summary-value">
                    <?= esc($summary['waiting_qc']) ?>
                </div>

            </div>
        </div>

        <!-- Diperiksa Hari Ini -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-summary-card">

                <div class="dashboard-summary-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <div class="dashboard-summary-label">
                    Diperiksa Hari Ini
                </div>

                <div class="dashboard-summary-value">
                    <?= esc($summary['inspected_today']) ?>
                </div>

            </div>
        </div>

        <!-- Lolos QC -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-summary-card">

                <div class="dashboard-summary-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <div class="dashboard-summary-label">
                    Lolos QC
                </div>

                <div class="dashboard-summary-value">
                    <?= esc($summary['passed']) ?>
                </div>

            </div>
        </div>

        <!-- Tidak Lolos QC -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dashboard-summary-card">

                <div class="dashboard-summary-icon">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div class="dashboard-summary-label">
                    Tidak Lolos QC
                </div>

                <div class="dashboard-summary-value">
                    <?= esc($summary['failed']) ?>
                </div>

            </div>
        </div>

    </div>

    <!-- Reinspection -->
    <div class="row g-3 mt-1">

        <div class="col-12 col-xl-4">
            <div class="dashboard-summary-card">

                <div class="dashboard-summary-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>

                <div class="dashboard-summary-label">
                    Pemeriksaan Ulang
                </div>

                <div class="dashboard-summary-value">
                    <?= esc($summary['reinspection']) ?>
                </div>

            </div>
        </div>

    </div>

    <!-- Recent Activity -->
    <div class="dashboard-section-card mt-4 p-4">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="fw-semibold mb-1">
                    Riwayat Pekerjaan Terbaru
                </h5>

                <p class="text-secondary small mb-0">
                    Aktivitas pemeriksaan QC terbaru.
                </p>
            </div>

            <span class="badge rounded-pill text-bg-light">
                Terbaru
            </span>
        </div>

        <div class="text-center py-5 text-secondary">
            <i class="bi bi-clock-history fs-2 d-block mb-2"></i>

            <div class="fw-medium">
                Belum ada riwayat pekerjaan.
            </div>

            <small>
                Riwayat pemeriksaan akan tampil di sini.
            </small>
        </div>

    </div>

</div>

<?= $this->endSection() ?>