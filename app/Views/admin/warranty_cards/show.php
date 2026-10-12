<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/digital-warranty-card.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$card = $card ?? [];

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

$status = (string) ($card['warranty_status'] ?? '-');

$statusClass = match (strtolower($status)) {
    'garansi aktif' => 'is-active',
    'aktif' => 'is-active',
    'garansi habis' => 'is-expired',
    'expired' => 'is-expired',
    default => 'is-neutral',
};

$cardPdfPath = $card['card_path'] ?? null;
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">Kartu Garansi Digital</h1>

                <p class="app-page-description">
                    Kartu garansi digital untuk produk yang telah berhasil diaktivasi.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <?php if ($cardPdfPath): ?>
                    <a
                        href="<?= site_url('admin/warranty-cards/' . $card['warranty_id'] . '/download') ?>"
                        class="btn-app-secondary">
                        <i class="bi bi-download"></i>
                        <span>Download PDF</span>
                    </a>
                <?php endif; ?>

                <button
                    type="button"
                    class="btn-app-primary"
                    id="dwcPrintButton">
                    <i class="bi bi-printer"></i>
                    <span>Cetak</span>
                </button>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="app-alert app-alert-success mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-12 col-lg-8">
            <article class="dwc-card" id="dwcPrintableCard">

                <div class="dwc-card-glow"></div>

                <header class="dwc-card-header">
                    <div class="dwc-brand">
                        <div class="dwc-brand-mark">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>
                            <strong>AIMPRO CARE</strong>
                            <span>Digital Warranty</span>
                        </div>
                    </div>

                    <div class="dwc-card-label">
                        KARTU GARANSI
                    </div>
                </header>

                <section class="dwc-number-section">
                    <span>Nomor Garansi</span>

                    <strong>
                        <?= esc($card['warranty_number'] ?? '-') ?>
                    </strong>

                    <div class="dwc-status <?= esc($statusClass) ?>">
                        <span></span>
                        <?= esc($status) ?>
                    </div>
                </section>

                <section class="dwc-product">
                    <div class="dwc-product-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div class="dwc-product-info">
                        <span>PRODUK TERDAFTAR</span>

                        <h2>
                            <?= esc($card['product_name'] ?? '-') ?>
                        </h2>

                        <p>
                            <i class="bi bi-upc-scan"></i>
                            <?= esc($card['serial_number'] ?? '-') ?>
                        </p>
                    </div>
                </section>

                <section class="dwc-details">
                    <div class="dwc-detail-item">
                        <span>
                            <i class="bi bi-person"></i>
                            Pemilik
                        </span>
                        <strong>
                            <?= esc($card['customer_name'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="dwc-detail-item">
                        <span>
                            <i class="bi bi-receipt"></i>
                            Invoice
                        </span>
                        <strong>
                            <?= esc($card['invoice_number'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="dwc-detail-item">
                        <span>
                            <i class="bi bi-calendar-event"></i>
                            Pembelian
                        </span>
                        <strong>
                            <?= esc($formatDate($card['purchase_date'] ?? null)) ?>
                        </strong>
                    </div>

                    <div class="dwc-detail-item">
                        <span>
                            <i class="bi bi-clock-history"></i>
                            Durasi
                        </span>
                        <strong>
                            <?= esc((string) ($card['duration_months'] ?? '-')) ?>
                            bulan
                        </strong>
                    </div>
                </section>

                <section class="dwc-period">
                    <div>
                        <span>GARANSI BERLAKU MULAI</span>
                        <strong>
                            <?= esc($formatDate($card['start_date'] ?? null)) ?>
                        </strong>
                    </div>

                    <i class="bi bi-arrow-right"></i>

                    <div>
                        <span>BERAKHIR</span>
                        <strong>
                            <?= esc($formatDate($card['end_date'] ?? null)) ?>
                        </strong>
                    </div>
                </section>

                <footer class="dwc-card-footer">
                    <div>
                        <i class="bi bi-shield-check"></i>
                        Garansi terdaftar secara digital
                    </div>

                    <span>AIMPRO CARE</span>
                </footer>

            </article>
        </div>

        <div class="col-12 col-lg-4">

            <section class="dwc-qr-card mb-4">
                <div class="dwc-qr-heading">
                    <div class="dwc-qr-icon">
                        <i class="bi bi-qr-code"></i>
                    </div>

                    <div>
                        <strong>Verifikasi Garansi</strong>
                        <span>Pindai QR untuk mengecek status.</span>
                    </div>
                </div>

                <div class="dwc-qr-image">
                    <img
                        src="<?= esc($card['qr_data_uri'] ?? '') ?>"
                        alt="QR Code verifikasi garansi">
                </div>

                <p class="dwc-qr-help">
                    QR Code ini mengarah ke halaman publik
                    verifikasi garansi AIMPRO CARE.
                </p>

                <a
                    href="<?= esc($card['public_url'] ?? '#') ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-app-primary w-100">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Buka Halaman Verifikasi</span>
                </a>
            </section>

            <section class="dwc-info-card">
                <div class="dwc-info-title">
                    <i class="bi bi-info-circle"></i>
                    Tentang Kartu Ini
                </div>

                <p>
                    Kartu ini merupakan bukti digital bahwa produk
                    telah terdaftar dan memiliki data garansi
                    di sistem AIMPRO CARE.
                </p>

                <div class="dwc-info-row">
                    <span>ID Garansi</span>
                    <strong>
                        #<?= esc((string) ($card['warranty_id'] ?? '-')) ?>
                    </strong>
                </div>

                <div class="dwc-info-row">
                    <span>Nomor Seri</span>
                    <strong>
                        <?= esc($card['serial_number'] ?? '-') ?>
                    </strong>
                </div>
            </section>

        </div>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/digital-warranty-card.js') ?>" defer></script>
<?= $this->endSection() ?>