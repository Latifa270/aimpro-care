<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$unit = $unit ?? [];

$unitId = (int) ($unit['id'] ?? 0);
$serialNumber = $unit['serial_number'] ?? '-';

$productParts = array_filter([
    $unit['brand'] ?? null,
    $unit['product_name'] ?? null,
    $unit['model'] ?? null,
], static fn($value) => trim((string) $value) !== '');

$productName = implode(' ', $productParts) ?: 'Produk tanpa nama';
$defaultDuration = (int) (
    $unit['warranty_duration_months'] ?? 12
);

$defaultDuration = max(1, min(120, $defaultDuration));
$today = date('Y-m-d');
$errors = session()->getFlashdata('errors') ?? [];

$old = static function (string $key, $default = '') {
    return old($key, $default);
};

$hasError = static function (string $key) use ($errors): bool {
    return isset($errors[$key]);
};
?>

<div class="container-fluid px-0">

    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-shield-check"></i>
                    MANAJEMEN GARANSI
                </span>

                <h1 class="app-page-title">Form Aktivasi Garansi</h1>

                <p class="app-page-description">
                    Lengkapi data pelanggan dan informasi pembelian untuk
                    mencatat penjualan serta mengaktifkan garansi produk.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Periksa kembali data yang diisi.</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form
        id="waActivationForm"
        action="<?= site_url('admin/warranty-activation') ?>"
        method="post"
        enctype="multipart/form-data"
        novalidate>
        <?= csrf_field() ?>

        <input
            type="hidden"
            name="product_unit_id"
            value="<?= esc((string) $unitId) ?>">

        <div class="row g-4">
            <div class="col-12 col-lg-8">

                <div class="app-card mb-4">
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="app-stat-icon app-stat-icon-primary">
                                <i class="bi bi-person-vcard"></i>
                            </div>

                            <div>
                                <h2 class="app-card-title">Data Pelanggan</h2>
                                <p class="app-card-subtitle mb-0">
                                    Informasi pemilik produk dan penerima garansi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body">
                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="waCustomerName" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="waCustomerName"
                                    name="name"
                                    class="form-control <?= $hasError('name') ? 'is-invalid' : '' ?>"
                                    value="<?= esc($old('name')) ?>"
                                    maxlength="50"
                                    placeholder="Masukkan nama pelanggan"
                                    autocomplete="name"
                                    required>

                                <?php if ($hasError('name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['name']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="waCustomerWhatsapp" class="form-label">
                                    Nomor WhatsApp <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="tel"
                                    id="waCustomerWhatsapp"
                                    name="whatsapp"
                                    class="form-control <?= $hasError('whatsapp') ? 'is-invalid' : '' ?>"
                                    value="<?= esc($old('whatsapp')) ?>"
                                    maxlength="20"
                                    placeholder="Contoh: 081234567890"
                                    autocomplete="tel"
                                    inputmode="tel"
                                    required>

                                <?php if ($hasError('whatsapp')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['whatsapp']) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="form-text">
                                    Nomor ini digunakan untuk notifikasi aktivasi garansi.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="waCustomerEmail" class="form-label">
                                    Email <span class="text-muted">(Opsional)</span>
                                </label>

                                <input
                                    type="email"
                                    id="waCustomerEmail"
                                    name="email"
                                    class="form-control <?= $hasError('email') ? 'is-invalid' : '' ?>"
                                    value="<?= esc($old('email')) ?>"
                                    maxlength="100"
                                    placeholder="nama@email.com"
                                    autocomplete="email">

                                <?php if ($hasError('email')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label for="waCustomerAddress" class="form-label">
                                    Alamat Lengkap <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    id="waCustomerAddress"
                                    name="address"
                                    class="form-control <?= $hasError('address') ? 'is-invalid' : '' ?>"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap pelanggan"
                                    autocomplete="street-address"
                                    required><?= esc($old('address')) ?></textarea>

                                <?php if ($hasError('address')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['address']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="app-card">
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="app-stat-icon app-stat-icon-primary">
                                <i class="bi bi-receipt"></i>
                            </div>

                            <div>
                                <h2 class="app-card-title">Informasi Pembelian</h2>
                                <p class="app-card-subtitle mb-0">
                                    Catat tanggal, referensi transaksi, dan bukti pembelian.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body">

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="waPurchaseDate" class="form-label">
                                    Tanggal Pembelian <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    id="waPurchaseDate"
                                    name="purchase_date"
                                    class="form-control <?= $hasError('purchase_date') ? 'is-invalid' : '' ?>"
                                    value="<?= esc($old('purchase_date', $today)) ?>"
                                    max="<?= esc($today) ?>"
                                    required>

                                <?php if ($hasError('purchase_date')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['purchase_date']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="waInvoiceNumber" class="form-label">
                                    Nomor Invoice <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="waInvoiceNumber"
                                    name="invoice_number"
                                    class="form-control <?= $hasError('invoice_number') ? 'is-invalid' : '' ?>"
                                    value="<?= esc($old('invoice_number')) ?>"
                                    maxlength="100"
                                    placeholder="Masukkan nomor invoice"
                                    required>

                                <?php if ($hasError('invoice_number')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['invoice_number']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="waMarketplace" class="form-label">
                                    Marketplace <span class="text-muted">(Opsional)</span>
                                </label>

                                <?php $marketplace = $old('marketplace'); ?>

                                <select
                                    id="waMarketplace"
                                    name="marketplace"
                                    class="form-select <?= $hasError('marketplace') ? 'is-invalid' : '' ?>">
                                    <option value="">Pilih marketplace</option>

                                    <?php
                                    $marketplaces = [
                                        'Shopee',
                                        'Tokopedia',
                                        'TikTok Shop',
                                        'Lazada',
                                        'Blibli',
                                        'Website Resmi',
                                        'Offline',
                                        'Lainnya',
                                    ];
                                    ?>

                                    <?php foreach ($marketplaces as $item): ?>
                                        <option
                                            value="<?= esc($item) ?>"
                                            <?= $marketplace === $item ? 'selected' : '' ?>>
                                            <?= esc($item) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if ($hasError('marketplace')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['marketplace']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="waMarketplaceOrder" class="form-label">
                                    Nomor Pesanan Marketplace <span class="text-muted">(Opsional)</span>
                                </label>

                                <input
                                    type="text"
                                    id="waMarketplaceOrder"
                                    name="marketplace_order_number"
                                    class="form-control <?= $hasError('marketplace_order_number') ? 'is-invalid' : '' ?>"
                                    value="<?= esc($old('marketplace_order_number')) ?>"
                                    maxlength="100"
                                    placeholder="Nomor pesanan">

                                <?php if ($hasError('marketplace_order_number')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['marketplace_order_number']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label for="waSalesNote" class="form-label">
                                    Catatan Penjualan <span class="text-muted">(Opsional)</span>
                                </label>

                                <textarea
                                    id="waSalesNote"
                                    name="sales_note"
                                    class="form-control <?= $hasError('sales_note') ? 'is-invalid' : '' ?>"
                                    rows="3"
                                    placeholder="Tambahkan catatan jika diperlukan"><?= esc($old('sales_note')) ?></textarea>

                                <?php if ($hasError('sales_note')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['sales_note']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Bukti Pembelian <span class="text-muted">(Opsional)</span>
                                </label>

                                <label class="app-upload" for="waReceipt">
                                    <input
                                        type="file"
                                        id="waReceipt"
                                        name="receipt"
                                        accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf"
                                        hidden>

                                    <span class="app-upload-icon">
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                    </span>

                                    <span class="app-upload-copy">
                                        <strong id="waFileName">Pilih bukti pembelian</strong>
                                        <span>JPG, PNG, WEBP, atau PDF · Maksimal 5 MB</span>
                                    </span>

                                    <span class="btn-app-secondary">
                                        Pilih
                                    </span>
                                </label>

                                <div class="d-flex align-items-center gap-3 mt-3" id="waFilePreview" hidden>
                                    <i class="bi bi-file-earmark-check text-success fs-4"></i>

                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold small" id="waFileNamePreview"></div>
                                        <div class="text-muted small" id="waFileSize"></div>
                                    </div>

                                    <button
                                        type="button"
                                        id="waRemoveReceipt"
                                        class="btn-app-danger btn-app-icon"
                                        aria-label="Hapus file">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                <?php if ($hasError('receipt')): ?>
                                    <div class="text-danger small mt-2">
                                        <?= esc($errors['receipt']) ?>
                                    </div>
                                <?php endif; ?>

                                <p class="text-muted small mt-3 mb-0">
                                    Bukti pembelian bersifat opsional. Jika tidak tersedia,
                                    transaksi tetap dapat diproses.
                                </p>
                            </div>

                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 mt-4 pt-4 border-top">
                            <a
                                href="<?= site_url('admin/warranty-activation') ?>"
                                class="btn-app-secondary">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>

                            <button
                                type="submit"
                                id="waSubmitButton"
                                class="btn-app-primary">
                                <i class="bi bi-shield-check"></i>
                                <span>Simpan & Aktifkan Garansi</span>
                            </button>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-4">
                <div class="app-card mb-4">
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="app-stat-icon app-stat-icon-primary">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <div>
                                <h2 class="app-card-title">Unit Terpilih</h2>
                                <p class="app-card-subtitle mb-0">
                                    Informasi unit yang akan diaktivasi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body">
                        <div class="mb-3">
                            <span class="app-unit-label">PRODUK</span>
                            <div class="fw-semibold"><?= esc($productName) ?></div>
                        </div>

                        <div class="mb-3">
                            <span class="app-unit-label">SERIAL NUMBER</span>
                            <div class="fw-semibold"><?= esc($serialNumber) ?></div>
                        </div>

                        <div>
                            <span class="app-unit-label">STATUS</span>
                            <span class="app-badge app-badge-success">
                                <span class="app-status-dot app-status-dot-success"></span>
                                Siap Dijual
                            </span>
                        </div>
                    </div>
                </div>

                <div class="app-card">
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="app-stat-icon app-stat-icon-success">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div>
                                <h2 class="app-card-title">Pengaturan Garansi</h2>
                                <p class="app-card-subtitle mb-0">
                                    Tentukan masa berlaku garansi produk.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body">

                        <div class="mb-3">
                            <label for="waDuration" class="form-label">
                                Durasi Garansi <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    type="number"
                                    id="waDuration"
                                    name="duration_months"
                                    class="form-control <?= $hasError('duration_months') ? 'is-invalid' : '' ?>"
                                    value="<?= esc((string) $old('duration_months', $defaultDuration)) ?>"
                                    min="1"
                                    max="120"
                                    step="1"
                                    required>

                                <span class="input-group-text">bulan</span>
                            </div>

                            <?php if ($hasError('duration_months')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= esc($errors['duration_months']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-text">
                                Masukkan durasi 1 sampai 120 bulan.
                            </div>
                        </div>

                        <div class="app-serial-block">
                            <span class="app-unit-label">PERKIRAAN MASA GARANSI</span>

                            <div class="d-flex justify-content-between gap-2 mt-2">
                                <span class="text-muted small">
                                    <i class="bi bi-calendar-event"></i>
                                    Mulai
                                </span>
                                <strong class="small" id="waPreviewStart">-</strong>
                            </div>

                            <div class="d-flex justify-content-between gap-2 mt-2">
                                <span class="text-muted small">
                                    <i class="bi bi-calendar-check"></i>
                                    Berakhir
                                </span>
                                <strong class="small" id="waPreviewEnd">-</strong>
                            </div>
                        </div>

                        <p class="text-muted small mt-3 mb-0">
                            Masa garansi dihitung berdasarkan tanggal pembelian.
                            Tanggal akhir yang ditampilkan merupakan perkiraan dan
                            akan ditetapkan oleh sistem saat transaksi berhasil.
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/warranty-activation-form.js') ?>" defer></script>
<?= $this->endSection() ?>