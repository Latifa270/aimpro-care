<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#F2EFE7">
    <meta name="description" content="AIMpro Care - After-Sales & Quality Control System">
    <meta name="theme-color" content="#22396F">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="AIMpro Care">

    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/icons/icon-192x192.png') ?>">
    <title><?= esc($title ?? 'Dashboard') ?> - AIMpro Care</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/aimpro.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body>

    <?php
    $currentPath = trim(service('uri')->getPath(), '/');
    $role = strtolower((string) session()->get('role'));

    $isAdmin = $role === 'admin';
    $isQc    = $role === 'qc';

    $isActive = static function (string $path, bool $exact = false) use ($currentPath): bool {
        $path = trim($path, '/');

        if ($exact) {
            return $currentPath === $path;
        }

        return $currentPath === $path
            || str_starts_with($currentPath, $path . '/');
    };
    ?>

    <aside class="app-sidebar" id="appSidebar">

        <div class="sidebar-brand">

            <div class="brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div class="brand-text">
                <span class="brand-name">AIMpro Care</span>
                <span class="brand-subtitle">After-Sales & QC</span>
            </div>

            <button
                type="button"
                class="sidebar-close-button"
                id="sidebarCloseButton"
                aria-label="Tutup menu">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <nav class="sidebar-nav">

            <?php if ($isAdmin): ?>

                <div class="nav-section-label">
                    Utama
                </div>

                <a
                    href="<?= site_url('admin/dashboard') ?>"
                    class="sidebar-link <?= $isActive('admin/dashboard', true) ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-grid-1x2"></i>
                    </span>
                    <span class="sidebar-link-text">Dashboard</span>
                </a>

                <div class="nav-section-label nav-section-spaced">
                    Data & Penjualan
                </div>

                <a
                    href="<?= site_url('admin/products') ?>"
                    class="sidebar-link <?= $isActive('admin/products') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    <span class="sidebar-link-text">Produk</span>
                </a>

                <a
                    href="<?= site_url('admin/units') ?>"
                    class="sidebar-link <?= ($isActive('admin/units') && !$isActive('admin/units/ready-for-sale')) ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    <span class="sidebar-link-text">Unit Produk</span>
                </a>

                <a
                    href="<?= site_url('admin/units/ready-for-sale') ?>"
                    class="sidebar-link <?= $isActive('admin/units/ready-for-sale') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>
                    <span class="sidebar-link-text">Siap Dijual</span>
                </a>

                <a
                    href="<?= site_url('admin/customers') ?>"
                    class="sidebar-link <?= $isActive('admin/customers') ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Data Pembeli</span>
                </a>

                <div class="nav-section-label nav-section-spaced">
                    Garansi
                </div>

                <a
                    href="<?= site_url('admin/warranty-activation') ?>"
                    class="sidebar-link <?= $isActive('admin/warranty-activation') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-patch-check"></i>
                    </span>
                    <span class="sidebar-link-text">Aktivasi Garansi</span>
                </a>

                <a
                    href="<?= site_url('admin/warranty-cards') ?>"
                    class="sidebar-link <?= $isActive('admin/warranty-cards') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-card-text"></i>
                    </span>
                    <span class="sidebar-link-text">Kartu Garansi</span>
                </a>

                <div class="nav-section-label nav-section-spaced">
                    After-Sales
                </div>

                <a
                    href="<?= site_url('admin/claims') ?>"
                    class="sidebar-link <?= $isActive('admin/claims') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-shield-exclamation"></i>
                    </span>
                    <span class="sidebar-link-text">Klaim Garansi</span>
                </a>

                <div class="nav-section-label nav-section-spaced">
                    Monitoring
                </div>

                <a
                    href="<?= site_url('admin/reports') ?>"
                    class="sidebar-link <?= $isActive('admin/reports') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>
                    <span class="sidebar-link-text">Laporan</span>
                </a>

                <a
                    href="<?= site_url('admin/activity-logs') ?>"
                    class="sidebar-link <?= $isActive('admin/activity-logs') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    <span class="sidebar-link-text">Activity Log</span>
                </a>

                <div class="nav-section-label nav-section-spaced">
                    Sistem
                </div>

                <a
                    href="<?= site_url('admin/settings') ?>"
                    class="sidebar-link <?= $isActive('admin/settings') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-gear"></i>
                    </span>
                    <span class="sidebar-link-text">Pengaturan</span>
                </a>


            <?php elseif ($isQc): ?>

                <div class="nav-section-label">
                    Utama
                </div>

                <a
                    href="<?= site_url('qc/dashboard') ?>"
                    class="sidebar-link <?= $isActive('qc/dashboard', true) ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-grid-1x2"></i>
                    </span>
                    <span class="sidebar-link-text">Dashboard QC</span>
                </a>

                <div class="nav-section-label nav-section-spaced">
                    Quality Control
                </div>

                <a
                    href="<?= site_url('qc/inspections') ?>"
                    class="sidebar-link <?= $isActive('qc/inspections') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </span>
                    <span class="sidebar-link-text">Pemeriksaan QC</span>
                </a>

                <a
                    href="<?= site_url('qc/units') ?>"
                    class="sidebar-link <?= $isActive('qc/units') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    <span class="sidebar-link-text">Unit Produk</span>
                </a>

                <a
                    href="<?= site_url('qc/history') ?>"
                    class="sidebar-link <?= $isActive('qc/history') ? 'active' : '' ?>">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    <span class="sidebar-link-text">Riwayat QC</span>
                </a>

            <?php endif; ?>

        </nav>

        <div class="sidebar-footer">

            <div class="sidebar-user">

                <div class="user-avatar">
                    <i class="bi bi-person"></i>
                </div>

                <div class="user-info">

                    <span class="user-name">
                        <?= esc(session()->get('name') ?? 'User') ?>
                    </span>

                    <span class="user-role">
                        <?= esc(session()->get('role') ?? '-') ?>
                    </span>

                </div>

            </div>

            <form
                action="<?= base_url('logout') ?>"
                method="post"
                class="logout-form">

                <?= csrf_field() ?>

                <button
                    type="submit"
                    class="logout-button"
                    title="Logout"
                    aria-label="Logout">

                    <i class="bi bi-box-arrow-right"></i>

                </button>

            </form>

        </div>

    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="app-main">

        <header class="app-topbar">

            <div class="topbar-left">

                <button
                    type="button"
                    class="mobile-menu-button"
                    id="mobileMenuButton"
                    aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="page-heading">
                    <span class="page-heading-label">
                        AIMpro Care
                    </span>

                    <h1>
                        <?= esc($heading ?? 'Dashboard') ?>
                    </h1>
                </div>

            </div>

            <div class="topbar-right">

                <div class="topbar-user">

                    <div class="topbar-avatar">
                        <i class="bi bi-person"></i>
                    </div>

                    <div class="topbar-user-info d-none d-sm-flex">
                        <span>
                            <?= esc(session()->get('name') ?? 'User') ?>
                        </span>

                        <small>
                            <?= esc(session()->get('role') ?? '-') ?>
                        </small>
                    </div>

                    <button
                        type="button"
                        id="pwaInstallButton"
                        class="btn-app-primary d-none d-md-inline-flex"
                        hidden>
                        <i class="bi bi-download"></i>
                        <span>Install App</span>
                    </button>

                </div>

            </div>

        </header>

        <section class="app-content">

            <?= $this->renderSection('content') ?>

        </section>

    </main>

    <!-- mobile admin -->
    <nav class="mobile-bottom-nav">

        <?php if ($isAdmin): ?>

            <a
                href="<?= site_url('admin/dashboard') ?>"
                class="mobile-nav-item <?= $isActive('admin/dashboard', true) ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2"></i>
                <span>Home</span>
            </a>

            <a
                href="<?= site_url('admin/units') ?>"
                class="mobile-nav-item <?= $isActive('admin/units') ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Unit</span>
            </a>

            <a
                href="<?= site_url('admin/units/ready-for-sale') ?>"
                class="mobile-nav-item <?= $isActive('admin/units/ready-for-sale') ? 'active' : '' ?>">
                <i class="bi bi-check-circle"></i>
                <span>Siap Jual</span>
            </a>

            <a
                href="<?= site_url('admin/warranty-activation') ?>"
                class="mobile-nav-item <?= $isActive('admin/warranty-activation') ? 'active' : '' ?>">
                <i class="bi bi-patch-check"></i>
                <span>Garansi</span>
            </a>

            <span class="mobile-nav-item mobile-nav-item-disabled">
                <i class="bi bi-tools"></i>
                <span>Klaim</span>
            </span>

            <!-- mobile nav QC -->
        <?php elseif ($isQc): ?>

            <a
                href="<?= site_url('qc/dashboard') ?>"
                class="mobile-nav-item <?= $isActive('qc/dashboard', true) ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2"></i>
                <span>Home</span>
            </a>

            <a
                href="<?= site_url('qc/units') ?>"
                class="mobile-nav-item <?= $isActive('qc/units') ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Unit</span>
            </a>

            <a
                href="<?= site_url('qc/inspections') ?>"
                class="mobile-nav-item <?= $isActive('qc/inspections') ? 'active' : '' ?>">
                <i class="bi bi-clipboard-check"></i>
                <span>QC</span>
            </a>

            <a
                href="<?= site_url('qc/history') ?>"
                class="mobile-nav-item <?= $isActive('qc/history') ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat</span>
            </a>

        <?php endif; ?>

    </nav>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="<?= base_url('assets/js/app.js') ?>"></script>

    <script
        src="<?= base_url('assets/js/pwa.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>