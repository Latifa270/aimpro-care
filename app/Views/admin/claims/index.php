<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$claims = $claims ?? [];
$totalClaims = count($claims);

$statusBadge = static function (?string $status): string {
    return match ($status) {
        'Pengajuan Baru'      => 'app-badge-primary',
        'Sedang Diverifikasi' => 'app-badge-warning',
        'Klaim Diterima'      => 'app-badge-success',
        'Klaim Ditolak'       => 'app-badge-danger',
        'Barang Diterima'     => 'app-badge-primary',
        'Sedang Diperiksa'    => 'app-badge-warning',
        'Sedang Diproses'     => 'app-badge-warning',
        'Selesai'             => 'app-badge-success',
        default               => 'app-badge-neutral',
    };
};

$statusIcon = static function (?string $status): string {
    return match ($status) {
        'Pengajuan Baru'      => 'bi-send',
        'Sedang Diverifikasi' => 'bi-search',
        'Klaim Diterima'      => 'bi-check-circle',
        'Klaim Ditolak'       => 'bi-x-circle',
        'Barang Diterima'     => 'bi-box-seam',
        'Sedang Diperiksa'    => 'bi-clipboard-check',
        'Sedang Diproses'     => 'bi-gear',
        'Selesai'             => 'bi-check2-circle',
        default               => 'bi-circle',
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

$getCustomerName = static function (array $claim): string {
    return trim((string) ($claim['customer_name'] ?? '')) ?: 'Pelanggan';
};

$pendingCount = count(array_filter(
    $claims,
    static fn(array $claim): bool => in_array(
        $claim['status'] ?? '',
        ['Pengajuan Baru', 'Sedang Diverifikasi'],
        true
    )
));

$completedCount = count(array_filter(
    $claims,
    static fn(array $claim): bool => ($claim['status'] ?? '') === 'Selesai'
));
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-shield-exclamation"></i>
                    AFTER-SALES
                </span>

                <h1 class="app-page-title">Klaim Garansi</h1>

                <p class="app-page-description">
                    Kelola pengajuan klaim garansi pelanggan dan pantau
                    proses penyelesaiannya.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-shield-exclamation"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-inbox"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Total Klaim</div>
                        <div class="app-stat-value"><?= number_format($totalClaims) ?></div>
                        <div class="app-stat-caption">Seluruh pengajuan klaim</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Perlu Diproses</div>
                        <div class="app-stat-value"><?= number_format($pendingCount) ?></div>
                        <div class="app-stat-caption">Menunggu tindakan admin</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Selesai</div>
                        <div class="app-stat-value"><?= number_format($completedCount) ?></div>
                        <div class="app-stat-caption">Klaim telah selesai</div>
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
                id="acClaimSearch"
                class="app-search-input"
                placeholder="Cari nomor garansi, pelanggan, WhatsApp, atau status..."
                autocomplete="off">
        </div>

        <span class="app-badge app-badge-primary" id="acResultLabel">
            <?= number_format($totalClaims) ?> Klaim
        </span>
    </div>

    <?php if ($totalClaims > 0): ?>

        <div class="app-table-card">
            <div class="app-table-wrapper">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Klaim</th>
                            <th>Pelanggan</th>
                            <th>Garansi</th>
                            <th>Pengajuan</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($claims as $claim): ?>
                            <?php
                            $claimId = (int) ($claim['id'] ?? 0);
                            $customerName = $getCustomerName($claim);
                            $warrantyNumber = $claim['warranty_number'] ?? '-';
                            $whatsapp = $claim['customer_whatsapp'] ?? ($claim['whatsapp'] ?? '-');
                            $status = $claim['status'] ?? 'Pengajuan Baru';
                            $createdAt = $formatDate($claim['created_at'] ?? null);

                            $searchText = strtolower(implode(' ', [
                                $claimId,
                                $warrantyNumber,
                                $customerName,
                                $whatsapp,
                                $status,
                                $claim['complaint'] ?? '',
                            ]));
                            ?>

                            <tr data-ac-row data-search="<?= esc($searchText) ?>">
                                <td data-label="Klaim">
                                    <div class="fw-semibold">
                                        Klaim #<?= esc((string) $claimId) ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($claim['handover_method'] ?? 'Pengajuan klaim') ?>
                                    </small>
                                </td>

                                <td data-label="Pelanggan">
                                    <div class="fw-semibold">
                                        <?= esc($customerName) ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($whatsapp) ?>
                                    </small>
                                </td>

                                <td data-label="Garansi">
                                    <span class="fw-semibold">
                                        <?= esc($warrantyNumber) ?>
                                    </span>
                                </td>

                                <td data-label="Pengajuan" class="text-muted">
                                    <?= esc($createdAt) ?>
                                </td>

                                <td data-label="Status">
                                    <span class="app-badge <?= esc($statusBadge($status)) ?>">
                                        <i class="bi <?= esc($statusIcon($status)) ?>"></i>
                                        <?= esc($status) ?>
                                    </span>
                                </td>

                                <td data-label="Aksi" class="text-end">
                                    <a
                                        href="<?= site_url('admin/claims/' . $claimId) ?>"
                                        class="btn-app-secondary btn-app-icon"
                                        title="Lihat detail klaim">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="acSearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Klaim tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Coba gunakan kata kunci lain untuk mencari pengajuan klaim.
                </p>

                <button
                    type="button"
                    class="btn-app-secondary mt-3"
                    id="acResetSearch">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Reset Pencarian</span>
                </button>
            </div>
        </div>

    <?php else: ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada pengajuan klaim
                </h3>

                <p class="app-empty-state-text">
                    Pengajuan klaim pelanggan akan muncul di halaman
                    ini setelah berhasil dikirim.
                </p>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('acClaimSearch');
        const rows = document.querySelectorAll('[data-ac-row]');
        const emptyState = document.getElementById('acSearchEmpty');
        const resultLabel = document.getElementById('acResultLabel');
        const resetBtn = document.getElementById('acResetSearch');

        if (searchInput && rows.length) {
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();
                let visible = 0;

                rows.forEach(row => {
                    const match = (row.dataset.search || '').includes(keyword);
                    row.hidden = !match;
                    if (match) visible++;
                });

                if (emptyState) emptyState.hidden = visible !== 0;
                if (resultLabel) resultLabel.textContent = visible + ' Klaim';
            });
        }

        if (resetBtn && searchInput) {
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });
        }

    });
</script>
<?= $this->endSection() ?>