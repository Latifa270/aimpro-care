<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$claim = $claim ?? [];
$history = $history ?? [];
$availableTransitions = $availableTransitions ?? [];

$status = $claim['status'] ?? 'Pengajuan Baru';

$statusBadgeMap = [
    'Pengajuan Baru'      => 'app-badge-primary',
    'Sedang Diverifikasi' => 'app-badge-warning',
    'Klaim Diterima'      => 'app-badge-success',
    'Klaim Ditolak'       => 'app-badge-danger',
    'Barang Diterima'     => 'app-badge-primary',
    'Sedang Diperiksa'    => 'app-badge-warning',
    'Sedang Diproses'     => 'app-badge-warning',
    'Selesai'             => 'app-badge-success',
];

$statusIconMap = [
    'Pengajuan Baru'      => 'bi-send',
    'Sedang Diverifikasi' => 'bi-search',
    'Klaim Diterima'      => 'bi-check-circle',
    'Klaim Ditolak'       => 'bi-x-circle',
    'Barang Diterima'     => 'bi-box-seam',
    'Sedang Diperiksa'    => 'bi-clipboard2-check',
    'Sedang Diproses'     => 'bi-gear',
    'Selesai'             => 'bi-check2-all',
];

$statusBadge = $statusBadgeMap[$status] ?? 'app-badge-neutral';
$statusIcon  = $statusIconMap[$status] ?? 'bi-shield-check';

$formatDate = static function ($date, string $format = 'd M Y, H:i') {
    if (empty($date)) {
        return '-';
    }

    try {
        return date($format, strtotime($date));
    } catch (\Throwable $e) {
        return '-';
    }
};

$customerName      = $claim['customer_name'] ?? '-';
$customerWhatsapp  = $claim['customer_whatsapp'] ?? '-';
$claimWhatsapp     = $claim['whatsapp'] ?? '-';
$handoverMethod    = $claim['handover_method'] ?? '-';
$shippingAddress   = $claim['shipping_address'] ?? '';
$complaint         = $claim['complaint'] ?? '-';
$damageStartDate   = $claim['damage_start_date'] ?? null;
$photoPath         = $claim['damage_photo'] ?? null;
$videoPath         = $claim['damage_video'] ?? null;
$photoUrl          = $photoPath ? base_url($photoPath) : null;
$videoUrl          = $videoPath ? base_url($videoPath) : null;
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3">
            <div>
                <a
                    href="<?= site_url('admin/claims') ?>"
                    class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Klaim
                </a>

                <span class="app-page-label">
                    <i class="bi bi-shield-exclamation"></i>
                    AFTER-SALES
                </span>

                <h1 class="app-page-title">
                    Klaim #<?= esc($claim['id'] ?? '-') ?>
                </h1>

                <p class="app-page-description">
                    Informasi lengkap dan proses penanganan klaim pelanggan.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="app-badge <?= esc($statusBadge) ?>">
                    <i class="bi <?= esc($statusIcon) ?>"></i>
                    <?= esc($status) ?>
                </span>
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

    <div class="row g-4">

        <div class="col-12 col-lg-8">

            <div class="app-card mb-4">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Informasi Pengajuan</h2>
                        <p class="app-card-subtitle mb-0">Ringkasan klaim garansi</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <span class="app-unit-label">NOMOR GARANSI</span>
                            <div class="fw-semibold">
                                <?= esc($claim['warranty_number'] ?? '-') ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <span class="app-unit-label">ID KLAIM</span>
                            <div class="fw-semibold">
                                #<?= esc($claim['id'] ?? '-') ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <span class="app-unit-label">TANGGAL PENGAJUAN</span>
                            <div class="fw-semibold">
                                <?= esc($formatDate($claim['created_at'] ?? null)) ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <span class="app-unit-label">MULAI KERUSAKAN</span>
                            <div class="fw-semibold">
                                <?= esc($formatDate($damageStartDate, 'd M Y')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-card mb-4">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Data Pembeli</h2>
                        <p class="app-card-subtitle mb-0">Informasi pelanggan</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-person"></i>
                    </div>
                </div>

                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-avatar">
                        <?= esc(strtoupper(substr(trim($customerName), 0, 1))) ?>
                    </div>

                    <div>
                        <div class="fw-semibold">
                            <?= esc($customerName) ?>
                        </div>

                        <div class="d-flex align-items-center gap-3 flex-wrap mt-1">
                            <span class="text-muted small">
                                <i class="bi bi-whatsapp"></i>
                                <?= esc($customerWhatsapp) ?>
                            </span>

                            <?php if ($claimWhatsapp): ?>
                                <span class="text-muted small">
                                    <i class="bi bi-phone"></i>
                                    Nomor klaim: <?= esc($claimWhatsapp) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-card mb-4">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Detail Kerusakan</h2>
                        <p class="app-card-subtitle mb-0">Keluhan dari pelanggan</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-chat-square-text"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <div class="app-serial-block">
                        <?= nl2br(esc($complaint)) ?>
                    </div>
                </div>
            </div>

            <div class="app-card mb-4">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Metode Penanganan</h2>
                        <p class="app-card-subtitle mb-0">Serah terima barang</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <div class="mb-3">
                        <span class="app-unit-label">METODE</span>
                        <div class="fw-semibold"><?= esc($handoverMethod) ?></div>
                    </div>

                    <?php if ($handoverMethod === 'Kurir' && $shippingAddress): ?>
                        <div>
                            <span class="app-unit-label">ALAMAT PENGIRIMAN</span>
                            <div class="app-serial-block">
                                <?= nl2br(esc($shippingAddress)) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="app-card mb-4">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Bukti Kerusakan</h2>
                        <p class="app-card-subtitle mb-0">Dokumentasi dari pelanggan</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-images"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <div class="row g-3">

                        <?php if ($photoUrl): ?>
                            <div class="col-12 col-md-6">
                                <a
                                    href="<?= esc($photoUrl) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-decoration-none text-reset">
                                    <div class="app-card">
                                        <img
                                            src="<?= esc($photoUrl) ?>"
                                            alt="Foto kerusakan"
                                            class="img-fluid rounded-3"
                                            loading="lazy">
                                        <div class="text-center small text-muted mt-2">
                                            <i class="bi bi-image"></i>
                                            Foto Kerusakan
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($videoUrl): ?>
                            <div class="col-12 col-md-6">
                                <div class="app-card">
                                    <video controls preload="metadata" class="w-100 rounded-3">
                                        <source src="<?= esc($videoUrl) ?>">
                                    </video>
                                    <div class="text-center small text-muted mt-2">
                                        <i class="bi bi-camera-video"></i>
                                        Video Kerusakan
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (! $photoUrl && ! $videoUrl): ?>
                            <div class="col-12">
                                <div class="app-empty-state">
                                    <div class="app-empty-state-icon">
                                        <i class="bi bi-image"></i>
                                    </div>
                                    <p class="app-empty-state-text mb-0">
                                        Tidak ada dokumentasi kerusakan.
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="app-card">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Timeline Klaim</h2>
                        <p class="app-card-subtitle mb-0">Riwayat perubahan status</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <?php if (! empty($history)): ?>
                        <div class="app-timeline">
                            <?php foreach ($history as $index => $item): ?>
                                <?php
                                $historyStatus = $item['status'] ?? '-';
                                $historyBadge  = $statusBadgeMap[$historyStatus] ?? 'app-badge-neutral';
                                $historyIcon   = $statusIconMap[$historyStatus] ?? 'bi-circle';
                                $isLast        = $index === array_key_last($history);
                                ?>
                                <div class="app-timeline-item <?= $isLast ? 'is-last' : '' ?>">
                                    <div class="app-timeline-marker <?= esc($historyBadge) ?>">
                                        <i class="bi <?= esc($historyIcon) ?>"></i>
                                    </div>

                                    <div class="app-timeline-content">
                                        <div class="app-timeline-top">
                                            <strong><?= esc($historyStatus) ?></strong>
                                            <time>
                                                <?= esc($formatDate($item['created_at'] ?? $item['updated_at'] ?? null)) ?>
                                            </time>
                                        </div>

                                        <?php if (! empty($item['note'])): ?>
                                            <p class="app-timeline-note">
                                                <?= nl2br(esc($item['note'])) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="app-empty-state">
                            <div class="app-empty-state-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <p class="app-empty-state-text mb-0">
                                Belum ada riwayat perubahan status.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-4">

            <div class="app-card mb-4">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Perbarui Status</h2>
                        <p class="app-card-subtitle mb-0">Tindakan admin</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <?php if (! empty($availableTransitions)): ?>
                        <form
                            action="<?= site_url('admin/claims/' . ($claim['id'] ?? 0) . '/status') ?>"
                            method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <span class="app-unit-label">STATUS SAAT INI</span>
                                <div>
                                    <span class="app-badge <?= esc($statusBadge) ?>">
                                        <i class="bi <?= esc($statusIcon) ?>"></i>
                                        <?= esc($status) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="claimStatus" class="form-label">
                                    Status Berikutnya <span class="text-danger">*</span>
                                </label>

                                <select
                                    id="claimStatus"
                                    name="status"
                                    class="form-select"
                                    required>
                                    <option value="">Pilih status</option>
                                    <?php foreach ($availableTransitions as $transition): ?>
                                        <option value="<?= esc($transition) ?>">
                                            <?= esc($transition) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="claimNote" class="form-label">
                                    Catatan <span class="text-muted">(Opsional)</span>
                                </label>

                                <textarea
                                    id="claimNote"
                                    name="note"
                                    class="form-control"
                                    rows="4"
                                    maxlength="5000"
                                    placeholder="Tambahkan catatan untuk riwayat klaim..."></textarea>
                            </div>

                            <button type="submit" class="btn-app-primary w-100">
                                <i class="bi bi-check2-circle"></i>
                                <span>Perbarui Status</span>
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="app-empty-state">
                            <div class="app-empty-state-icon">
                                <i class="bi bi-check2-all"></i>
                            </div>

                            <h3 class="app-empty-state-title">
                                Klaim Selesai
                            </h3>

                            <p class="app-empty-state-text">
                                Tidak ada perubahan status berikutnya yang tersedia.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="app-card">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Referensi Garansi</h2>
                        <p class="app-card-subtitle mb-0">Data terkait</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <div class="mb-3">
                        <span class="app-unit-label">NOMOR GARANSI</span>
                        <div class="fw-semibold">
                            <?= esc($claim['warranty_number'] ?? '-') ?>
                        </div>
                    </div>

                    <div>
                        <span class="app-unit-label">ID PENJUALAN</span>
                        <div class="fw-semibold">
                            #<?= esc($claim['sales_id'] ?? '-') ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/claims-detail.js') ?>" defer></script>
<?= $this->endSection() ?>