<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$units = $units ?? [];
$totalUnits = count($units);
?>

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
                    Daftar unit yang menunggu pemeriksaan QC.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-clipboard-check"></i>
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

    <div class="app-toolbar">
        <div class="app-search">
            <i class="bi bi-search"></i>
            <input
                type="search"
                id="unitSearch"
                class="app-search-input"
                placeholder="Cari produk, brand, model, atau serial..."
                autocomplete="off">
        </div>

        <span class="app-badge app-badge-primary" id="unitResultCount">
            <?= number_format($totalUnits) ?> Unit
        </span>
    </div>

    <?php if (empty($units)): ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Tidak ada unit yang menunggu QC
                </h3>

                <p class="app-empty-state-text">
                    Unit akan tampil di sini setelah berstatus Menunggu QC.
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
                            <th>Status</th>
                            <th class="text-end" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($units as $index => $unit): ?>
                            <?php
                            $searchText = strtolower(implode(' ', [
                                $unit['product_name']  ?? '',
                                $unit['brand']         ?? '',
                                $unit['model']         ?? '',
                                $unit['serial_number'] ?? '',
                                $unit['status']        ?? '',
                            ]));
                            ?>

                            <tr data-unit-row data-search="<?= esc($searchText) ?>">
                                <td data-label="No"><?= $index + 1 ?></td>

                                <td data-label="Produk">
                                    <div class="fw-semibold">
                                        <?= esc($unit['product_name']) ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($unit['brand']) ?>
                                        <?= esc($unit['model']) ?>
                                    </small>
                                </td>

                                <td data-label="Serial Number">
                                    <code class="fw-semibold">
                                        <?= esc($unit['serial_number']) ?>
                                    </code>
                                </td>

                                <td data-label="Status">
                                    <span class="app-badge app-badge-neutral">
                                        <?= esc($unit['status']) ?>
                                    </span>
                                </td>

                                <td data-label="Aksi" class="text-end">
                                    <a
                                        href="<?= site_url('qc/inspections/' . $unit['id']) ?>"
                                        class="btn-app-soft-sm">
                                        <i class="bi bi-clipboard-check"></i>
                                        <span>Mulai QC</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="unitSearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Unit tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Coba gunakan kata kunci lain untuk mencari unit.
                </p>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/qc-inspections.js') ?>" defer></script>
<?= $this->endSection() ?>