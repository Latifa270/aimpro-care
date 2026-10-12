<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$report = $report ?? [];
$units  = $report['units'] ?? [];
$warranties = $report['warranties'] ?? [];
$claims = $report['claims'] ?? [];
$qc     = $report['qc'] ?? [];
$claimsByStatus = $report['claims_by_status'] ?? [];
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">Laporan</h1>

                <p class="app-page-description">
                    Rekap data unit, garansi, klaim, dan QC.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-bar-chart"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Total Unit</div>
                        <div class="app-stat-value-sm"><?= number_format($units['total'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-success">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Garansi Aktif</div>
                        <div class="app-stat-value-sm"><?= number_format($warranties['active'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Total Klaim</div>
                        <div class="app-stat-value-sm"><?= number_format($claims['total'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-danger">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">QC Lolos</div>
                        <div class="app-stat-value-sm"><?= number_format($qc['passed'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-card mb-4">
        <div class="app-card-body">
            <form action="<?= site_url('admin/reports') ?>" method="get">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4">
                        <label for="start_date" class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control"
                            value="<?= esc($startDate) ?>">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="end_date" class="form-label">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="form-control"
                            value="<?= esc($endDate) ?>">
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-app-primary flex-grow-1">
                                <i class="bi bi-search"></i>
                                <span>Terapkan</span>
                            </button>

                            <a href="<?= site_url('admin/reports') ?>" class="btn-app-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="app-toolbar">
        <div>
            <span class="text-muted small">
                Periode: <?= esc($startDate) ?> s.d. <?= esc($endDate) ?>
            </span>
        </div>

        <div class="d-flex gap-2">
            <a
                href="<?= site_url('admin/reports/export-excel?start_date=' . $startDate . '&end_date=' . $endDate) ?>"
                class="btn-app-secondary">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Export Excel</span>
            </a>

            <a
                href="<?= site_url('admin/reports/export-pdf?start_date=' . $startDate . '&end_date=' . $endDate) ?>"
                class="btn-app-secondary">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-6">
            <div class="app-card h-100">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Rekap QC</h2>
                        <p class="app-card-subtitle mb-0">
                            Hasil pemeriksaan QC pada periode terpilih
                        </p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <div class="d-flex flex-column gap-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Lolos QC</span>
                            <strong><?= number_format($qc['passed'] ?? 0) ?></strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tidak Lolos QC</span>
                            <strong><?= number_format($qc['failed'] ?? 0) ?></strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Perlu Pemeriksaan Ulang</span>
                            <strong><?= number_format($qc['reinspection'] ?? 0) ?></strong>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="app-card h-100">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Klaim per Status</h2>
                        <p class="app-card-subtitle mb-0">
                            Distribusi klaim berdasarkan status
                        </p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <?php if (empty($claimsByStatus)): ?>
                        <div class="app-empty-state">
                            <div class="app-empty-state-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <p class="app-empty-state-text mb-0">
                                Belum ada data klaim.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($claimsByStatus as $status => $count): ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><?= esc($status) ?></span>
                                    <strong><?= number_format($count) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>