<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$cards = $cards ?? [];

$formatDate = static function ($date): string {
    if (empty($date)) {
        return '-';
    }

    try {
        return (new DateTime($date))->format('d M Y');
    } catch (Throwable $e) {
        return (string) $date;
    }
};

$getProductName = static function (array $card): string {
    return trim(
        implode(' ', array_filter([
            $card['product_brand'] ?? null,
            $card['product_name'] ?? null,
            $card['product_model'] ?? null,
        ]))
    ) ?: '-';
};

$getStatusClass = static function ($status): string {
    return match (strtolower(trim((string) $status))) {
        'aktif', 'garansi aktif' => 'app-badge-success',
        'expired', 'habis', 'garansi habis' => 'app-badge-danger',
        default => 'app-badge-neutral',
    };
};

$totalCards   = count($cards);
$activeCount  = count(array_filter($cards, static function (array $card): bool {
    $status = strtolower(trim((string) ($card['warranty_status'] ?? '')));
    return in_array($status, ['aktif', 'garansi aktif'], true);
}));
$pdfCount     = count(array_filter($cards, static fn(array $c): bool => ! empty($c['card_path'])));
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">Kartu Garansi</h1>

                <p class="app-page-description">
                    Kelola kartu garansi digital yang telah diterbitkan untuk pelanggan.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-credit-card-2-front"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Total Kartu</div>
                        <div class="app-stat-value"><?= number_format($totalCards) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-success">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Garansi Aktif</div>
                        <div class="app-stat-value"><?= number_format($activeCount) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">PDF Tersedia</div>
                        <div class="app-stat-value"><?= number_format($pdfCount) ?></div>
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
                id="awcSearch"
                class="app-search-input"
                placeholder="Cari nomor garansi, serial, produk, atau pembeli..."
                autocomplete="off">
        </div>

        <span class="app-badge app-badge-primary" id="awcResultCount">
            <?= number_format($totalCards) ?> Kartu
        </span>
    </div>

    <?php if (! empty($cards)): ?>

        <div class="app-table-card">
            <div class="app-table-wrapper">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Kartu Garansi</th>
                            <th>Produk</th>
                            <th>Pembeli</th>
                            <th>Periode Garansi</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($cards as $card): ?>
                            <?php
                            $productName = $getProductName($card);
                            $status      = (string) ($card['warranty_status'] ?? '-');

                            $searchText = strtolower(implode(' ', [
                                $card['warranty_number'] ?? '',
                                $card['serial_number'] ?? '',
                                $productName,
                                $card['customer_name'] ?? '',
                                $card['invoice_number'] ?? '',
                            ]));
                            ?>

                            <tr data-awc-row data-search="<?= esc($searchText) ?>">
                                <td data-label="Kartu Garansi">
                                    <div class="fw-semibold">
                                        <?= esc($card['warranty_number'] ?? '-') ?>
                                    </div>

                                    <small class="text-muted">
                                        Serial: <?= esc($card['serial_number'] ?? '-') ?>
                                    </small>
                                </td>

                                <td data-label="Produk">
                                    <div class="fw-semibold">
                                        <?= esc($productName) ?>
                                    </div>

                                    <small class="text-muted">
                                        Invoice: <?= esc($card['invoice_number'] ?? '-') ?>
                                    </small>
                                </td>

                                <td data-label="Pembeli">
                                    <div class="fw-semibold">
                                        <?= esc($card['customer_name'] ?? '-') ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($card['customer_whatsapp'] ?? '-') ?>
                                    </small>
                                </td>

                                <td data-label="Periode Garansi">
                                    <div class="fw-semibold">
                                        <?= esc($formatDate($card['start_date'] ?? null)) ?>
                                    </div>

                                    <small class="text-muted">
                                        s.d. <?= esc($formatDate($card['end_date'] ?? null)) ?>
                                    </small>
                                </td>

                                <td data-label="Status">
                                    <span class="app-badge <?= esc($getStatusClass($status)) ?>">
                                        <?= esc($status) ?>
                                    </span>

                                    <?php if (! empty($card['card_path'])): ?>
                                        <div class="small text-success mt-1">
                                            <i class="bi bi-file-earmark-check"></i>
                                            PDF tersedia
                                        </div>
                                    <?php else: ?>
                                        <div class="small text-warning mt-1">
                                            <i class="bi bi-file-earmark"></i>
                                            PDF belum dibuat
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Aksi" class="text-end">
                                    <a
                                        href="<?= site_url('admin/warranty-cards/' . $card['warranty_id']) ?>"
                                        class="btn-app-secondary btn-app-icon"
                                        title="Lihat kartu">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="awcSearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Kartu tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Tidak ada kartu garansi yang sesuai dengan pencarian.
                </p>
            </div>
        </div>

    <?php else: ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada kartu garansi
                </h3>

                <p class="app-empty-state-text">
                    Kartu garansi akan muncul di sini setelah warranty berhasil diaktivasi.
                </p>

                <a
                    href="<?= site_url('admin/warranty-activation') ?>"
                    class="btn-app-primary mt-3">
                    <i class="bi bi-shield-plus"></i>
                    <span>Aktivasi Garansi</span>
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin-warranty-cards.js') ?>" defer></script>
<?= $this->endSection() ?>