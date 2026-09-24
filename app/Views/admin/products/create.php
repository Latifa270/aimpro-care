<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <div class="mb-4">
        <h2 class="fw-semibold mb-1">
            Tambah Produk
        </h2>

        <p class="text-secondary mb-0">
            Tambahkan produk baru ke master data.
        </p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>

        <div class="alert alert-danger rounded-4 border-0 mb-4">

            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>

        </div>

    <?php endif; ?>

    <div class="dashboard-section-card p-4">

        <form action="<?= site_url('admin/products') ?>" method="post">

            <?= csrf_field() ?>

            <div class="row g-3">

                <div class="col-12">

                    <label for="name" class="form-label">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        value="<?= old('name') ?>"
                        maxlength="150"
                        required>

                </div>

                <div class="col-12 col-md-6">

                    <label for="brand" class="form-label">
                        Brand
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="brand"
                        name="brand"
                        value="<?= old('brand') ?>"
                        maxlength="100"
                        required>

                </div>

                <div class="col-12 col-md-6">

                    <label for="model" class="form-label">
                        Model
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="model"
                        name="model"
                        value="<?= old('model') ?>"
                        maxlength="100"
                        required>

                </div>

                <div class="col-12">

                    <label for="description" class="form-label">
                        Deskripsi
                    </label>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="5"><?= old('description') ?></textarea>

                </div>

            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">

                <a
                    href="<?= site_url('admin/products') ?>"
                    class="btn btn-light rounded-pill px-4">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-dark rounded-pill px-4">
                    <i class="bi bi-check-lg me-1"></i>
                    Simpan Produk
                </button>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>