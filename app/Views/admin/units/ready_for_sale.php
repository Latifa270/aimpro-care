<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">Daftar Siap Jual</h1>

                <p class="app-page-description">
                    Daftar unit yang telah dinyatakan lolos pemeriksaan QC dan siap dijual.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-check-circle"></i>
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
                id="readySearch"
                class="app-search-input"
                placeholder="Cari produk, brand, model, atau serial..."
                autocomplete="off">
        </div>

        <a
            href="<?= site_url('admin/units') ?>"
            class="btn-app-secondary">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Unit</span>
        </a>
    </div>

    <?php if (empty($units)): ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada unit siap jual
                </h3>

                <p class="app-empty-state-text">
                    Unit akan muncul di halaman ini setelah dinyatakan Lolos QC.
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
                            <th>Terakhir Diperbarui</th>
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

                            <tr data-ready-row data-search="<?= esc($searchText) ?>">
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
                                    <span class="app-badge app-badge-success">
                                        <?= esc($unit['status']) ?>
                                    </span>
                                </td>

                                <td data-label="Terakhir Diperbarui" class="text-muted">
                                    <?= esc($unit['updated_at'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="readySearchEmpty" hidden>
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
<script src="<?= base_url('assets/js/ready-for-sale.js') ?>" defer></script>
<?= $this->endSection() ?>