<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$customers = $customers ?? [];
$totalCustomers = count($customers);
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">Data Pembeli</h1>

                <p class="app-page-description">
                    Data pembeli yang tersimpan dari proses aktivasi garansi.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-person-vcard"></i>
            </div>
        </div>
    </div>

    <div class="app-toolbar">
        <div class="app-search">
            <i class="bi bi-search"></i>
            <input
                type="search"
                id="customerSearch"
                class="app-search-input"
                placeholder="Cari nama, WhatsApp, email..."
                autocomplete="off">
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="app-badge app-badge-primary" id="customerVisibleCount">
                <?= esc($totalCustomers) ?> Pembeli
            </span>
        </div>
    </div>

    <?php if (empty($customers)): ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-people"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada data pembeli
                </h3>

                <p class="app-empty-state-text">
                    Data pembeli akan muncul setelah proses aktivasi garansi dilakukan.
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
                            <th>Pembeli</th>
                            <th>WhatsApp</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($customers as $index => $customer): ?>
                            <?php
                            $name     = trim((string) ($customer['name'] ?? ''));
                            $whatsapp = trim((string) ($customer['whatsapp'] ?? ''));
                            $email    = trim((string) ($customer['email'] ?? ''));
                            $address  = trim((string) ($customer['address'] ?? ''));

                            $initial   = $name !== '' ? strtoupper(mb_substr($name, 0, 1)) : '?';
                            $createdAt = !empty($customer['created_at'])
                                ? date('d-m-Y', strtotime($customer['created_at']))
                                : '—';

                            $searchText = strtolower(implode(' ', [
                                $name,
                                $whatsapp,
                                $email,
                                $address,
                            ]));
                            ?>

                            <tr data-customer-row data-search="<?= esc($searchText) ?>">
                                <td data-label="No"><?= $index + 1 ?></td>

                                <td data-label="Pembeli">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="app-avatar">
                                            <?= esc($initial) ?>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                <?= esc($name ?: '—') ?>
                                            </div>

                                            <small class="text-muted">
                                                ID #<?= esc($customer['id'] ?? '—') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="WhatsApp">
                                    <?php if ($whatsapp !== ''): ?>
                                        <a
                                            href="https://wa.me/<?= esc(preg_replace('/\D+/', '', $whatsapp)) ?>"
                                            class="text-decoration-none fw-medium"
                                            target="_blank"
                                            rel="noopener noreferrer">
                                            <i class="bi bi-whatsapp text-success"></i>
                                            <?= esc($whatsapp) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Email">
                                    <?php if ($email !== ''): ?>
                                        <?= esc($email) ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Alamat">
                                    <?php if ($address !== ''): ?>
                                        <span title="<?= esc($address) ?>">
                                            <?= esc($address) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Terdaftar" class="text-muted">
                                    <i class="bi bi-calendar3"></i>
                                    <?= esc($createdAt) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="customerSearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Data tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Tidak ada pembeli yang cocok dengan pencarian Anda.
                </p>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin-customers.js') ?>" defer></script>
<?= $this->endSection() ?>