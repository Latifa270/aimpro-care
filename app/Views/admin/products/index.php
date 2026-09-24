<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">

        <div>
            <h2 class="fw-semibold mb-1">
                Produk
            </h2>

            <p class="text-secondary mb-0">
                Kelola master produk AIMpro Care.
            </p>
        </div>

        <a href="<?= site_url('admin/products/create') ?>"
            class="btn btn-dark rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Produk
        </a>

    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success rounded-4 border-0 mb-4">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-section-card p-3 p-md-4">

        <?php if (empty($products)): ?>

            <div class="text-center py-5 text-secondary">
                <i class="bi bi-box-seam fs-2 d-block mb-2"></i>

                <div class="fw-medium">
                    Belum ada produk.
                </div>

                <small>
                    Tambahkan produk pertama untuk mulai mengelola unit.
                </small>
            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($products as $product): ?>

                            <tr>
                                <td class="fw-medium">
                                    <?= esc($product['name']) ?>
                                </td>

                                <td>
                                    <?= esc($product['brand']) ?>
                                </td>

                                <td>
                                    <?= esc($product['model']) ?>
                                </td>

                                <td class="text-secondary">
                                    <?= esc($product['created_at']) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>