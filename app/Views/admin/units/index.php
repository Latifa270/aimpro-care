<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$units = $units ?? [];
$totalUnits = count($units);

$statusBadge = static function (?string $status): string {
    return match ($status) {
        'Draft'                    => 'app-badge-neutral',
        'Menunggu QC'              => 'app-badge-primary',
        'Perlu Pemeriksaan Ulang'  => 'app-badge-warning',
        'Tidak Lolos QC'           => 'app-badge-danger',
        'Siap Dijual'              => 'app-badge-success',
        'Terjual'                  => 'app-badge-primary',
        'Dikembalikan'             => 'app-badge-warning',
        default                    => 'app-badge-neutral',
    };
};
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-upc-scan"></i>
                    MASTER DATA
                </span>

                <h1 class="app-page-title">Unit Produk</h1>

                <p class="app-page-description">
                    Kelola unit dan serial number produk.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-upc-scan"></i>
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

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Periksa kembali data berikut:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
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

        <button
            type="button"
            class="btn-app-primary"
            data-bs-toggle="modal"
            data-bs-target="#addUnitModal">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Unit</span>
        </button>
    </div>

    <?php if (empty($units)): ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-upc-scan"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada unit
                </h3>

                <p class="app-empty-state-text">
                    Tambahkan unit dan serial number untuk mulai proses QC.
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
                            <th>Dibuat</th>
                            <th class="text-end" style="width: 80px;">Aksi</th>
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
                                    <span class="app-badge <?= esc($statusBadge($unit['status'] ?? null)) ?>">
                                        <?= esc($unit['status']) ?>
                                    </span>
                                </td>

                                <td data-label="Dibuat" class="text-muted">
                                    <?= esc($unit['created_at']) ?>
                                </td>

                                <td data-label="Aksi" class="text-end">
                                    <div class="d-inline-flex gap-2">

                                        <?php if ($unit['status'] === 'Perlu Pemeriksaan Ulang'): ?>
                                            <form
                                                action="<?= site_url('admin/units/' . $unit['id'] . '/resend-to-qc') ?>"
                                                method="post"
                                                class="d-inline">
                                                <?= csrf_field() ?>
                                                <button
                                                    type="submit"
                                                    class="btn-app-primary btn-sm"
                                                    title="Kirim Ulang ke QC">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                    <span>Kirim Ulang</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <button
                                            type="button"
                                            class="btn-app-danger btn-app-icon"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteUnitModal"
                                            data-unit='<?= esc(json_encode($unit), 'attr') ?>'
                                            data-action="<?= site_url('admin/units/' . $unit['id'] . '/delete') ?>"
                                            title="Hapus Unit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
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

<?= $this->include('admin/units/create') ?>
<?= $this->include('admin/units/delete') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/units.js') ?>" defer></script>
<?= $this->endSection() ?>