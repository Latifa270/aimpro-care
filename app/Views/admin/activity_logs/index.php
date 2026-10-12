<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$logs = $logs ?? [];
$totalLogs = count($logs);
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-clock-history"></i>
                    MONITORING
                </span>

                <h1 class="app-page-title">Activity Log</h1>

                <p class="app-page-description">
                    Catatan aktivitas Admin dan QC pada sistem AIMpro Care.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    <div class="app-toolbar">
        <div class="app-search">
            <i class="bi bi-search"></i>
            <input
                type="search"
                id="logSearch"
                class="app-search-input"
                placeholder="Cari pengguna, aktivitas, atau deskripsi..."
                autocomplete="off">
        </div>

        <span class="app-badge app-badge-primary" id="logResultCount">
            <?= number_format($totalLogs) ?> Aktivitas
        </span>
    </div>

    <?php if (! empty($logs)): ?>

        <div class="app-table-card">
            <div class="app-table-wrapper">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No.</th>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($logs as $index => $log): ?>
                            <?php
                            $userName   = $log['user_name'] ?? '-';
                            $userRole   = $log['user_role'] ?? '-';
                            $action     = $log['action'] ?? '-';
                            $description = $log['description'] ?? '-';
                            $ipAddress  = $log['ip_address'] ?? '-';

                            $createdAt  = $log['created_at'] ?? null;
                            $datePart   = $createdAt ? date('d/m/Y', strtotime($createdAt)) : '-';
                            $timePart   = $createdAt ? date('H:i:s', strtotime($createdAt)) : '-';

                            $searchText = strtolower(implode(' ', [
                                $userName,
                                $userRole,
                                $action,
                                $description,
                                $ipAddress,
                            ]));
                            ?>

                            <tr data-log-row data-search="<?= esc($searchText) ?>">
                                <td data-label="No."><?= $index + 1 ?></td>

                                <td data-label="Pengguna">
                                    <div class="fw-semibold">
                                        <?= esc($userName) ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= esc($userRole) ?>
                                    </small>
                                </td>

                                <td data-label="Aktivitas">
                                    <span class="app-badge app-badge-primary">
                                        <?= esc($action) ?>
                                    </span>
                                </td>

                                <td data-label="Deskripsi">
                                    <div style="min-width: 220px; max-width: 400px;">
                                        <?= esc($description) ?>
                                    </div>
                                </td>

                                <td data-label="IP Address">
                                    <small class="text-muted">
                                        <?= esc($ipAddress) ?>
                                    </small>
                                </td>

                                <td data-label="Waktu">
                                    <div><?= esc($datePart) ?></div>
                                    <small class="text-muted"><?= esc($timePart) ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="logSearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Aktivitas tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Coba gunakan kata kunci lain untuk mencari aktivitas.
                </p>
            </div>
        </div>

    <?php else: ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum Ada Aktivitas
                </h3>

                <p class="app-empty-state-text">
                    Aktivitas pengguna akan muncul di halaman ini setelah tercatat oleh sistem.
                </p>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/activity-logs.js') ?>" defer></script>
<?= $this->endSection() ?>