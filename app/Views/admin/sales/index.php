<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$units = $units ?? [];
$totalUnits = count($units);
?>

<div class="container-fluid py-3 py-lg-4">

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">

        <div>
            <div class="text-uppercase small fw-semibold text-primary mb-1">
                Penjualan
            </div>

            <h1 class="h3 fw-bold mb-1">
                Unit Siap Dijual
            </h1>

            <p class="text-muted mb-0">
                Pilih unit produk untuk mencatat penjualan dan mengaktifkan garansi.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                <i class="bi bi-check-circle me-1"></i>
                <?= number_format($totalUnits, 0, ',', '.') ?> unit siap dijual
            </span>
        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">

                    <div
                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:48px;height:48px;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>

                    <div>
                        <div class="small text-muted">
                            Unit Siap Dijual
                        </div>

                        <div class="fs-4 fw-bold">
                            <?= number_format($totalUnits, 0, ',', '.') ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">

                    <div
                        class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:48px;height:48px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>

                    <div>
                        <div class="small text-muted">
                            Berikutnya
                        </div>

                        <div class="fw-semibold">
                            Aktivasi Garansi
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-3 px-lg-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

                <div>
                    <h2 class="h6 fw-bold mb-1">
                        Daftar Unit
                    </h2>

                    <p class="small text-muted mb-0">
                        Unit dengan status <strong>Siap Dijual</strong> dapat diproses.
                    </p>
                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <?php if (empty($units)): ?>

                <div class="text-center px-4 py-5">

                    <div
                        class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:64px;height:64px;">
                        <i class="bi bi-box-seam fs-3 text-muted"></i>
                    </div>

                    <h3 class="h6 fw-bold">
                        Belum ada unit siap dijual
                    </h3>

                    <p class="text-muted small mb-4">
                        Unit dengan status Siap Dijual akan muncul di halaman ini.
                    </p>

                    <a
                        href="<?= site_url('admin/units') ?>"
                        class="btn btn-outline-primary">
                        <i class="bi bi-box me-1"></i>
                        Lihat Data Unit
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive d-none d-lg-block">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="px-4" style="width:80px;">No</th>
                                <th>Produk</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th class="text-end px-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($units as $index => $unit): ?>

                                <?php
                                $productName = trim(implode(' ', array_filter([
                                    $unit['brand'] ?? '',
                                    $unit['product_name'] ?? '',
                                    $unit['model'] ?? '',
                                ])));

                                $productName = $productName !== ''
                                    ? $productName
                                    : 'Produk tanpa nama';

                                $unitId = (int) ($unit['id'] ?? 0);
                                $serialNumber = $unit['serial_number'] ?? '-';
                                ?>

                                <tr>
                                    <td class="px-4 text-muted">
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($productName) ?>
                                        </div>

                                        <div class="small text-muted">
                                            Unit #<?= $unitId ?>
                                        </div>
                                    </td>

                                    <td>
                                        <code class="small">
                                            <?= esc($serialNumber) ?>
                                        </code>
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Siap Dijual
                                        </span>
                                    </td>

                                    <td class="text-end px-4">

                                        <a
                                            href="<?= site_url('admin/warranty-activation/' . $unitId) ?>"
                                            class="btn btn-primary btn-sm">

                                            <i class="bi bi-shield-check me-1"></i>
                                            Aktivasi Garansi

                                        </a>

                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <div class="d-lg-none">

                    <?php foreach ($units as $index => $unit): ?>

                        <?php
                        $productName = trim(implode(' ', array_filter([
                            $unit['brand'] ?? '',
                            $unit['product_name'] ?? '',
                            $unit['model'] ?? '',
                        ])));

                        $productName = $productName !== ''
                            ? $productName
                            : 'Produk tanpa nama';

                        $unitId = (int) ($unit['id'] ?? 0);
                        $serialNumber = $unit['serial_number'] ?? '-';
                        ?>

                        <div class="border-bottom p-3">

                            <div class="d-flex align-items-start justify-content-between gap-3">

                                <div class="d-flex align-items-start gap-3">

                                    <div
                                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:44px;height:44px;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            <?= esc($productName) ?>
                                        </div>

                                        <div class="small text-muted mt-1">
                                            Unit #<?= $unitId ?>
                                        </div>

                                    </div>

                                </div>

                                <span class="badge rounded-pill bg-success-subtle text-success">
                                    Siap
                                </span>

                            </div>

                            <div class="mt-3">

                                <div class="small text-muted mb-1">
                                    Serial Number
                                </div>

                                <code>
                                    <?= esc($serialNumber) ?>
                                </code>

                            </div>

                            <div class="mt-3">

                                <a
                                    href="<?= site_url('admin/warranty-activation/' . $unitId) ?>"
                                    class="btn btn-primary w-100">

                                    <i class="bi bi-shield-check me-1"></i>
                                    Aktivasi Garansi
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<?= $this->endSection() ?>