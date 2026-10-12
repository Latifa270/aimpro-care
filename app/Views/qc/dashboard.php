<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$summary = $summary ?? [];
$recent  = $recent  ?? [];

$resultBadge = static function (?string $result): string {
    return match ($result) {
        'Lolos QC'                => 'app-badge-success',
        'Tidak Lolos QC'          => 'app-badge-danger',
        'Perlu Pemeriksaan Ulang' => 'app-badge-warning',
        default                   => 'app-badge-neutral',
    };
};

$formatDate = static function ($date): string {
    if (empty($date)) {
        return '-';
    }

    try {
        return date('d M Y, H:i', strtotime((string) $date));
    } catch (Throwable $e) {
        return '-';
    }
};
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">
                    Halo, <?= esc(session()->get('name')) ?>
                </h1>

                <p class="app-page-description">
                    Pantau aktivitas Quality Control hari ini.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Menunggu QC</div>
                        <div class="app-stat-value-sm"><?= number_format($summary['waiting_qc'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Diperiksa Hari Ini</div>
                        <div class="app-stat-value-sm"><?= number_format($summary['inspected_today'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Lolos QC</div>
                        <div class="app-stat-value-sm"><?= number_format($summary['passed'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Tidak Lolos QC</div>
                        <div class="app-stat-value-sm"><?= number_format($summary['failed'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Pemeriksaan Ulang</div>
                        <div class="app-stat-value-sm"><?= number_format($summary['reinspection'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-card">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Riwayat Pekerjaan Terbaru</h2>
                <p class="app-card-subtitle mb-0">
                    Aktivitas pemeriksaan QC terbaru oleh Anda.
                </p>
            </div>

            <span class="app-badge app-badge-primary">
                Terbaru
            </span>
        </div>

        <div class="app-card-body p-0">
            <?php if (empty($recent)): ?>
                <div class="app-empty-state">
                    <div class="app-empty-state-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <h3 class="app-empty-state-title">
                        Belum ada riwayat pekerjaan
                    </h3>

                    <p class="app-empty-state-text">
                        Riwayat pemeriksaan akan tampil di sini.
                    </p>
                </div>
            <?php else: ?>
                <div class="app-table-wrapper">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Produk</th>
                                <th>Serial Number</th>
                                <th>Hasil QC</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>

                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($item['product_name'] ?? '-') ?>
                                        </div>

                                        <small class="text-muted">
                                            <?= esc($item['brand'] ?? '') ?>
                                            <?= esc($item['model'] ?? '') ?>
                                        </small>
                                    </td>

                                    <td>
                                        <code class="fw-semibold">
                                            <?= esc($item['serial_number'] ?? '-') ?>
                                        </code>
                                    </td>

                                    <td>
                                        <span class="app-badge <?= esc($resultBadge($item['result'] ?? null)) ?>">
                                            <?= esc($item['result'] ?? '-') ?>
                                        </span>
                                    </td>

                                    <td class="text-muted">
                                        <?= esc($formatDate($item['inspection_date'] ?? null)) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>