<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="app-page-title">Produk</h1>

                <p class="app-page-description">
                    Kelola master produk AIMpro Care.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-box-seam"></i>
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
                id="productSearch"
                class="app-search-input"
                placeholder="Cari produk, brand, model..."
                autocomplete="off">
        </div>

        <button
            type="button"
            class="btn-app-primary"
            data-bs-toggle="modal"
            data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Produk</span>
        </button>
    </div>

    <?php if (empty($products)): ?>

        <div class="app-card">
            <div class="app-empty-state">
                <div class="app-empty-state-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Belum ada produk
                </h3>

                <p class="app-empty-state-text">
                    Tambahkan produk pertama untuk mulai mengelola unit.
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
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Dibuat</th>
                            <th class="text-end" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($products as $index => $product): ?>
                            <?php
                            $searchText = strtolower(implode(' ', [
                                $product['name']  ?? '',
                                $product['brand'] ?? '',
                                $product['model'] ?? '',
                            ]));
                            ?>

                            <tr data-product-row data-search="<?= esc($searchText) ?>">
                                <td data-label="No"><?= $index + 1 ?></td>

                                <td data-label="Produk">
                                    <div class="fw-semibold">
                                        <?= esc($product['name']) ?>
                                    </div>
                                </td>

                                <td data-label="Brand">
                                    <?= esc($product['brand']) ?>
                                </td>

                                <td data-label="Model">
                                    <?= esc($product['model']) ?>
                                </td>

                                <td data-label="Dibuat">
                                    <?= esc($product['created_at']) ?>
                                </td>

                                <td data-label="Aksi" class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <button
                                            type="button"
                                            class="btn-app-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProductModal"
                                            data-product='<?= esc(json_encode($product), 'attr') ?>'
                                            data-action="<?= site_url('admin/products/' . $product['id'] . '/update') ?>"
                                            title="Edit produk">
                                            <i class="bi bi-pencil"></i>
                                            <span>Edit</span>
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-app-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteProductModal"
                                            data-product='<?= esc(json_encode($product), 'attr') ?>'
                                            data-action="<?= site_url('admin/products/' . $product['id'] . '/delete') ?>"
                                            title="Hapus Produk">
                                            <i class="bi bi-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="app-empty-state" id="productSearchEmpty" hidden>
                <div class="app-empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="app-empty-state-title">
                    Produk tidak ditemukan
                </h3>

                <p class="app-empty-state-text">
                    Coba gunakan kata kunci lain untuk mencari produk.
                </p>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->include('admin/products/create') ?>
<?= $this->include('admin/products/edit') ?>
<?= $this->include('admin/products/delete') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/products.js') ?>" defer></script>
<?= $this->endSection() ?>