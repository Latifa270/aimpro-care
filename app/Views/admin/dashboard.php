<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$units          = $summary['units']            ?? [];
$warranties     = $summary['warranties']       ?? [];
$claims         = $summary['claims']           ?? [];
$chart          = $summary['chart']            ?? [];
$claimsByStatus = $summary['claims_by_status'] ?? [];
$topProducts    = $summary['topProducts']      ?? [];
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-grid-1x2"></i>
                    DASHBOARD
                </span>

                <h1 class="app-page-title">
                    Halo, <?= esc(session()->get('name')) ?>
                </h1>

                <p class="app-page-description">
                    Pantau aktivitas penjualan, garansi, dan klaim.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-bar-chart"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Total Unit</div>
                        <div class="app-stat-value-sm"><?= number_format($units['total'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Siap Dijual</div>
                        <div class="app-stat-value-sm"><?= number_format($units['ready_for_sale'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-cart-check"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Terjual</div>
                        <div class="app-stat-value-sm"><?= number_format($units['sold'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Garansi Aktif</div>
                        <div class="app-stat-value-sm"><?= number_format($warranties['active'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Segera Berakhir</div>
                        <div class="app-stat-value-sm"><?= number_format($warranties['expiring_soon'] ?? 0) ?></div>
                        <div class="app-stat-caption">30 hari ke depan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-danger">
                        <i class="bi bi-shield-x"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Garansi Berakhir</div>
                        <div class="app-stat-value-sm"><?= number_format($warranties['expired'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-inbox"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Klaim Baru</div>
                        <div class="app-stat-value-sm"><?= number_format($claims['new'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="app-card">
                <div class="app-card-body d-flex align-items-center gap-3">
                    <div class="app-stat-icon app-stat-icon-warning">
                        <i class="bi bi-gear"></i>
                    </div>

                    <div>
                        <div class="app-stat-label">Sedang Diproses</div>
                        <div class="app-stat-value-sm"><?= number_format($claims['in_progress'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-12 col-lg-8">
            <div class="app-card h-100">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Grafik Aktivasi Garansi</h2>
                        <p class="app-card-subtitle mb-0">12 bulan terakhir</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <?php if (empty($chart)): ?>
                        <div class="app-empty-state">
                            <div class="app-empty-state-icon">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <p class="app-empty-state-text mb-0">
                                Belum ada data aktivasi garansi.
                            </p>
                        </div>
                    <?php else: ?>
                        <?php
                        $maxValue = max(array_column($chart, 'total')) ?: 1;
                        ?>

                        <div class="app-bar-chart">
                            <div class="app-bar-chart-bars" style="height: 220px;">
                                <?php foreach ($chart as $item): ?>
                                    <?php
                                    $height = $maxValue > 0
                                        ? round(($item['total'] / $maxValue) * 100)
                                        : 0;
                                    ?>
                                    <div class="app-bar-chart-item">
                                        <div class="app-bar-chart-value">
                                            <?= esc((string) $item['total']) ?>
                                        </div>

                                        <div
                                            class="app-bar-chart-bar"
                                            style="height: <?= max(4, $height) ?>%;"
                                            title="<?= esc($item['label']) ?>: <?= esc((string) $item['total']) ?>">
                                        </div>

                                        <div class="app-bar-chart-label">
                                            <?= esc($item['label']) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="app-card h-100">
                <div class="app-card-header">
                    <div>
                        <h2 class="app-card-title">Klaim per Status</h2>
                        <p class="app-card-subtitle mb-0">Distribusi klaim</p>
                    </div>

                    <div class="app-stat-icon app-stat-icon-primary">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                </div>

                <div class="app-card-body">
                    <?php if (empty($claimsByStatus)): ?>
                        <div class="app-empty-state">
                            <div class="app-empty-state-icon">
                                <i class="bi bi-pie-chart"></i>
                            </div>
                            <p class="app-empty-state-text mb-0">
                                Belum ada data klaim.
                            </p>
                        </div>
                    <?php else: ?>
                        <?php
                        $total = array_sum($claimsByStatus);
                        $colors = [
                            '#22396F',
                            '#3368A0',
                            '#66A3BF',
                            '#2E7D68',
                            '#B7791F',
                            '#B33F56',
                            '#C8DFDB',
                            '#8290A3',
                        ];

                        $circumference = 2 * 3.14159 * 40;
                        $offset = 0;
                        ?>

                        <div class="d-flex justify-content-center mb-3">
                            <svg width="160" height="160" viewBox="0 0 100 100">
                                <?php $i = 0; ?>
                                <?php foreach ($claimsByStatus as $status => $count): ?>
                                    <?php
                                    $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                    $dashArray = ($percentage / 100) * $circumference;
                                    $dashOffset = - ($offset / 100) * $circumference;
                                    $color = $colors[$i % count($colors)];
                                    ?>

                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="40"
                                        fill="transparent"
                                        stroke="<?= $color ?>"
                                        stroke-width="12"
                                        stroke-dasharray="<?= $dashArray ?> <?= $circumference ?>"
                                        stroke-dashoffset="<?= $dashOffset ?>"
                                        transform="rotate(-90 50 50)" />

                                    <?php
                                    $offset += $percentage;
                                    $i++;
                                    ?>
                                <?php endforeach; ?>

                                <circle cx="50" cy="50" r="28" fill="white" />
                                <text x="50" y="48" text-anchor="middle" font-size="10" font-weight="bold" fill="#172640">
                                    <?= $total ?>
                                </text>
                                <text x="50" y="58" text-anchor="middle" font-size="6" fill="#8290A3">
                                    Klaim
                                </text>
                            </svg>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <?php $i = 0; ?>
                            <?php foreach ($claimsByStatus as $status => $count): ?>
                                <?php
                                $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                                $color = $colors[$i % count($colors)];
                                ?>

                                <div class="d-flex align-items-center gap-2 small">
                                    <span
                                        style="width: 12px; height: 12px; background: <?= $color ?>; border-radius: 3px;">
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <?= esc($status) ?>
                                    </span>
                                    <strong>
                                        <?= $count ?> (<?= $percentage ?>%)
                                    </strong>
                                </div>

                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="app-card">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Produk Terlaris</h2>
                <p class="app-card-subtitle mb-0">Top 5 produk berdasarkan penjualan</p>
            </div>

            <div class="app-stat-icon app-stat-icon-success">
                <i class="bi bi-trophy"></i>
            </div>
        </div>

        <div class="app-card-body p-0">
            <?php if (empty($topProducts)): ?>
                <div class="app-empty-state">
                    <div class="app-empty-state-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <p class="app-empty-state-text mb-0">
                        Belum ada data penjualan.
                    </p>
                </div>
            <?php else: ?>
                <div class="app-table-wrapper">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Produk</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th class="text-end" style="width: 120px;">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $index => $product): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($product['name']) ?>
                                        </div>
                                    </td>
                                    <td><?= esc($product['brand']) ?></td>
                                    <td><?= esc($product['model']) ?></td>
                                    <td class="text-end">
                                        <span class="app-badge app-badge-success">
                                            <?= number_format($product['total_sold'] ?? 0) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>