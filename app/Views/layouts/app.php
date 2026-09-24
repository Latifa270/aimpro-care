<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <meta
        name="theme-color"
        content="#f5f7fb">

    <meta
        name="description"
        content="AIMpro Care - After-Sales & Quality Control System">

    <title><?= esc($title ?? 'Dashboard') ?> - AIMpro Care</title>

    <!-- Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body>

    <aside class="app-sidebar" id="appSidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div class="brand-text">
                <span class="brand-name">AIMpro Care</span>
                <span class="brand-subtitle">After-Sales & QC</span>
            </div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section-label">
                Menu
            </div>

            <a
                href="<?= session()->get('role') === 'Admin'
                            ? base_url('admin/dashboard')
                            : base_url('qc/dashboard') ?>"
                class="sidebar-link active">
                <span class="sidebar-link-icon">
                    <i class="bi bi-grid-1x2"></i>
                </span>

                <span class="sidebar-link-text">
                    Dashboard
                </span>
            </a>

            <?php if (session()->get('role') === 'Admin'): ?>

                <div class="nav-section-label mt-4">
                    Administrator
                </div>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Produk
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-upc-scan"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Unit
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-cart3"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Sales
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-patch-check"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Garansi
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-tools"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Klaim
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Activity Log
                    </span>
                </a>

            <?php elseif (session()->get('role') === 'QC'): ?>

                <div class="nav-section-label mt-4">
                    Quality Control
                </div>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Pemeriksaan QC
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Unit
                    </span>
                </a>

                <a href="#" class="sidebar-link">
                    <span class="sidebar-link-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Riwayat QC
                    </span>
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
                    title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

        </div>

    </aside>

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

                </div>

            </div>

        </header>

        <section class="app-content">

            <?= $this->renderSection('content') ?>

        </section>

    </main>

    <nav class="mobile-bottom-nav">

        <a
            href="<?= session()->get('role') === 'Admin'
                        ? base_url('admin/dashboard')
                        : base_url('qc/dashboard') ?>"
            class="mobile-nav-item active">
            <i class="bi bi-grid-1x2"></i>
            <span>Home</span>
        </a>

        <?php if (session()->get('role') === 'Admin'): ?>

            <a href="#" class="mobile-nav-item">
                <i class="bi bi-box-seam"></i>
                <span>Unit</span>
            </a>

            <a href="#" class="mobile-nav-item">
                <i class="bi bi-patch-check"></i>
                <span>Garansi</span>
            </a>

            <a href="#" class="mobile-nav-item">
                <i class="bi bi-tools"></i>
                <span>Klaim</span>
            </a>

        <?php else: ?>

            <a href="#" class="mobile-nav-item">
                <i class="bi bi-clipboard-check"></i>
                <span>QC</span>
            </a>

            <a href="#" class="mobile-nav-item">
                <i class="bi bi-box-seam"></i>
                <span>Unit</span>
            </a>

            <a href="#" class="mobile-nav-item">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat</span>
            </a>

        <?php endif; ?>

    </nav>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script
        src="<?= base_url('assets/js/app.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>