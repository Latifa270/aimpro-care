<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$units = $units ?? [];
$totalUnits = count($units);

$formatProductName = static function (array $unit): string {
    $parts = array_filter([
        $unit['brand'] ?? null,
        $unit['product_name'] ?? null,
        $unit['model'] ?? null,
    ], static fn($value) => trim((string) $value) !== '');

    return implode(' ', $parts) ?: 'Produk tanpa nama';
};
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-shield-check"></i>
                    MANAJEMEN GARANSI
                </span>

                <h1 class="app-page-title">Aktivasi Garansi</h1>

                <p class="app-page-description">
                    Pilih unit produk yang siap dijual untuk memulai proses
                    pencatatan penjualan dan aktivasi garansi pelanggan.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-3 mb-4">
        <div class="app-card" style="flex: 0 1 280px;">
            <div class="app-card-body d-flex align-items-center gap-3">
                <div class="app-stat-icon app-stat-icon-primary">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <div class="app-stat-label">Unit Siap Dijual</div>
                    <div class="app-stat-value-sm">
                        <?= number_format($totalUnits, 0, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-card" style="flex: 0 1 280px;">
            <div class="app-card-body d-flex align-items-center gap-3">
                <div class="app-stat-icon app-stat-icon-success">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <div class="app-stat-label">Status Ketersediaan</div>
                    <div class="app-stat-value-sm d-flex align-items-center gap-2">
                        <span class="app-status-dot app-status-dot-success"></span>
                        Siap Diproses
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-toolbar">
        <div class="app-search">
            <i class="bi bi-search"></i>
            <input
                type="search"
                id="waUnitSearch"
                class="app-search-input"
                placeholder="Cari produk, merek, model, atau serial number..."
                autocomplete="off">
        </div>

        <span class="app-badge app-badge-primary" id="waResultLabel">
            <?= number_format($totalUnits, 0, ',', '.') ?> Unit
        </span>
    </div>

    <?php if ($totalUnits > 0): ?>

        <div class="app-grid" id="waUnitGrid">
            <?php foreach ($units as $unit): ?>
                <?php
                $productName = $formatProductName($unit);
                $serialNumber = $unit['serial_number'] ?? '-';
                $unitId = (int) ($unit['id'] ?? 0);

                $searchText = strtolower(implode(' ', [
                    $productName,
                    $unit['brand'] ?? '',
                    $unit['product_name'] ?? '',
                    $unit['model'] ?? '',
                    $serialNumber,
                ]));
                ?>

                <article
                    class="app-card app-unit-card"
                    data-wa-row
                    data-search="<?= esc($searchText, 'attr') ?>">

                    <div class="app-card-body">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="app-stat-icon app-stat-icon-primary">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <span class="app-badge app-badge-success">
                                <span class="app-status-dot app-status-dot-success"></span>
                                Siap Dijual
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="app-unit-label">PRODUK</span>

                            <h3 class="app-unit-title">
                                <?= esc($productName) ?>
                            </h3>
                        </div>

                        <div class="app-serial-block">
                            <span class="app-unit-label">SERIAL NUMBER</span>

                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <span class="app-serial-text">
                                    <?= esc($serialNumber) ?>
                                </span>

                                <?php if ($serialNumber !== '-'): ?>
                                    <button
                                        type="button"
                                        class="btn-app-secondary btn-app-icon"
                                        data-copy="<?= esc($serialNumber, 'attr') ?>"
                                        title="Salin serial number">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <div class="app-card-footer">
                        <div class="app-unit-id">
                            <span>ID Unit</span>
                            <strong>#<?= esc((string) $unitId) ?></strong>
                        </div>

                        <a
                            href="<?= site_url('admin/warranty-activation/' . $unitId) ?>"
                            class="btn-app-primary">
                            <span>Aktivasi</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>

        <div class="app-card" id="waSearchEmpty" hidden>
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Unit tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Coba gunakan kata kunci lain atau periksa kembali
                    serial number yang dimasukkan.
                </p>

                <button
                    type="button"
                    class="btn-app-secondary mt-3"
                    id="waResetSearch">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Reset Pencarian</span>
                </button>
            </div>
        </div>

    <?php else: ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada unit siap dijual
                </h3>

                <p class="app-empty-state-text">
                    Unit yang telah memenuhi persyaratan dan berstatus
                    Siap Dijual akan muncul di halaman ini.
                </p>

                <a
                    href="<?= site_url('admin/units') ?>"
                    class="btn-app-secondary mt-3">
                    <i class="bi bi-arrow-left"></i>
                    <span>Lihat Data Unit</span>
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/warranty-activation.js') ?>" defer></script>
<?= $this->endSection() ?>