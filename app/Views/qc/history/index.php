<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$inspections = $inspections ?? [];
$totalInspections = count($inspections);

$resultBadge = static function (?string $result): string {
    return match ($result) {
        'Lolos QC'                 => 'app-badge-success',
        'Tidak Lolos QC'           => 'app-badge-danger',
        'Perlu Pemeriksaan Ulang'  => 'app-badge-warning',
        default                    => 'app-badge-neutral',
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
                <span class="app-page-label">
                    <i class="bi bi-clock-history"></i>
                    QUALITY CONTROL
                </span>

                <h1 class="app-page-title">Riwayat QC</h1>

                <p class="app-page-description">
                    Daftar unit yang pernah Anda periksa.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    <div class="app-toolbar">
        <div class="app-search">
            <i class="bi bi-search"></i>
            <input
                type="search"
                id="historySearch"
                class="app-search-input"
                placeholder="Cari produk, brand, model, atau serial..."
                autocomplete="off">
        </div>

        <span class="app-badge app-badge-primary" id="historyResultCount">
            <?= number_format($totalInspections) ?> Riwayat
        </span>
    </div>

    <?php if (empty($inspections)): ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada riwayat pemeriksaan
                </h3>

                <p class="app-empty-state-text">
                    Riwayat pemeriksaan akan muncul di sini setelah Anda melakukan QC.
                </p>
            </div>
        </div>

    <?php else: ?>

        <div class="app-table-card">
            <div class="app-table-wrapper">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Produk</th>
                            <th>Serial Number</th>
                            <th>Tanggal</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($inspections as $index => $inspection): ?>
                            <?php
                            $searchText = strtolower(implode(' ', [
                                $inspection['product_name']  ?? '',
                                $inspection['brand']         ?? '',
                                $inspection['model']         ?? '',
                                $inspection['serial_number'] ?? '',
                                $inspection['result']        ?? '',
                            ]));
                            ?>

                            <tr data-history-row data-search="<?= esc($searchText) ?>">
                                <td data-label="No"><?= $index + 1 ?></td>

                                <td data-label="Produk">
                                    <div class="fw-semibold">
                                        <?= esc($inspection['product_name']) ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($inspection['brand']) ?>
                                        <?= esc($inspection['model']) ?>
                                    </small>
                                </td>

                                <td data-label="Serial Number">
                                    <code class="fw-semibold">
                                        <?= esc($inspection['serial_number']) ?>
                                    </code>
                                </td>

                                <td data-label="Tanggal" class="text-muted">
                                    <?= esc($formatDate($inspection['inspection_date'] ?? null)) ?>
                                </td>

                                <td data-label="Hasil">
                                    <span class="app-badge <?= esc($resultBadge($inspection['result'] ?? null)) ?>">
                                        <?= esc($inspection['result']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="historySearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Riwayat tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Coba gunakan kata kunci lain untuk mencari riwayat.
                </p>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/qc-history.js') ?>" defer></script>
<?= $this->endSection() ?>