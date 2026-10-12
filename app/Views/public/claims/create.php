<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/public-claim.css') ?>">

<?php
$token = $token ?? '';

$errors = session()->getFlashdata('errors') ?? [];
$error  = session()->getFlashdata('error');
?>

<main class="pc-page">
    <div class="pc-container">

        <header class="pc-header">
            <div class="pc-brand-mark" aria-hidden="true">
                <i class="bi bi-shield-check"></i>
            </div>

            <div class="pc-brand-copy">
                <span class="pc-brand-name">AIMpro Care</span>
                <span class="pc-brand-subtitle">
                    After-Sales & Quality Control
                </span>
            </div>
        </header>

        <section class="pc-card">

            <div class="pc-card-header">
                <span class="pc-eyebrow">
                    <i class="bi bi-patch-check"></i>
                    Layanan Garansi
                </span>

                <h1 class="pc-title">
                    Pengajuan Klaim Garansi
                </h1>

                <p class="pc-description">
                    Lengkapi informasi kerusakan dan bukti pendukung
                    untuk mengajukan klaim garansi produk Anda.
                </p>
            </div>

            <div class="pc-card-body">

                <?php if ($error): ?>
                    <div class="pc-alert pc-alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle"></i>

                        <div>
                            <?= esc($error) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (! empty($errors)): ?>
                    <div class="pc-alert pc-alert-danger" role="alert">
                        <div>
                            <strong>Periksa kembali data berikut:</strong>

                            <ul>
                                <?php foreach ($errors as $validationError): ?>
                                    <li><?= esc($validationError) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <form
                    id="publicClaimForm"
                    action="<?= base_url('garansi/' . rawurlencode($token) . '/klaim') ?>"
                    method="post"
                    enctype="multipart/form-data">

                    <?= csrf_field() ?>

                    <section class="pc-section">
                        <div class="pc-section-heading">
                            <div class="pc-section-icon">
                                <i class="bi bi-person"></i>
                            </div>

                            <div>
                                <h2>Informasi Kontak</h2>
                                <p>Gunakan nomor WhatsApp yang terdaftar pada garansi.</p>
                            </div>
                        </div>

                        <div class="pc-field">
                            <label for="whatsapp" class="pc-label">
                                Nomor WhatsApp
                                <span class="pc-required">*</span>
                            </label>

                            <input
                                type="tel"
                                class="pc-input"
                                id="whatsapp"
                                name="whatsapp"
                                value="<?= esc(old('whatsapp')) ?>"
                                maxlength="20"
                                placeholder="Contoh: 081234567890"
                                autocomplete="tel"
                                required>

                            <div class="pc-help">
                                Nomor harus sesuai dengan data WhatsApp pada garansi.
                            </div>
                        </div>
                    </section>

                    <section class="pc-section">
                        <div class="pc-section-heading">
                            <div class="pc-section-icon">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </div>

                            <div>
                                <h2>Informasi Kerusakan</h2>
                                <p>Jelaskan kondisi kerusakan produk secara rinci.</p>
                            </div>
                        </div>

                        <div class="pc-field">
                            <label for="complaint" class="pc-label">
                                Keluhan Kerusakan
                                <span class="pc-required">*</span>
                            </label>

                            <textarea
                                class="pc-input pc-textarea"
                                id="complaint"
                                name="complaint"
                                maxlength="5000"
                                minlength="10"
                                placeholder="Jelaskan kerusakan yang dialami produk secara rinci..."
                                required><?= esc(old('complaint')) ?></textarea>

                            <div class="pc-field-footer">
                                <span class="pc-help">
                                    Minimal 10 karakter dan maksimal 5.000 karakter.
                                </span>

                                <span
                                    class="pc-counter"
                                    id="complaintCount">
                                    0/5000
                                </span>
                            </div>
                        </div>

                        <div class="pc-field">
                            <label for="damage_start_date" class="pc-label">
                                Tanggal Mulai Kerusakan
                                <span class="pc-required">*</span>
                            </label>

                            <input
                                type="date"
                                class="pc-input"
                                id="damage_start_date"
                                name="damage_start_date"
                                value="<?= esc(old('damage_start_date')) ?>"
                                max="<?= date('Y-m-d') ?>"
                                required>

                            <div class="pc-help">
                                Pilih tanggal ketika kerusakan mulai terjadi.
                            </div>
                        </div>
                    </section>

                    <section class="pc-section">
                        <div class="pc-section-heading">
                            <div class="pc-section-icon">
                                <i class="bi bi-camera"></i>
                            </div>

                            <div>
                                <h2>Bukti Kerusakan</h2>
                                <p>Unggah bukti untuk membantu proses pemeriksaan klaim.</p>
                            </div>
                        </div>

                        <div class="pc-field">
                            <label for="damage_photo" class="pc-label">
                                Foto Kerusakan
                                <span class="pc-required">*</span>
                            </label>

                            <label
                                class="pc-upload"
                                for="damage_photo">

                                <span class="pc-upload-icon">
                                    <i class="bi bi-image"></i>
                                </span>

                                <span class="pc-upload-content">
                                    <strong id="photoFileName">
                                        Pilih foto kerusakan
                                    </strong>

                                    <small>
                                        JPG, JPEG, PNG, atau WebP · Maksimal 5 MB
                                    </small>
                                </span>

                                <span class="pc-upload-action">
                                    Pilih
                                </span>
                            </label>

                            <input
                                type="file"
                                class="pc-file-input"
                                id="damage_photo"
                                name="damage_photo"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                required>
                        </div>

                        <div class="pc-field">
                            <label for="damage_video" class="pc-label">
                                Video Kerusakan
                                <span class="pc-optional">(Opsional)</span>
                            </label>

                            <label
                                class="pc-upload"
                                for="damage_video">

                                <span class="pc-upload-icon">
                                    <i class="bi bi-camera-video"></i>
                                </span>

                                <span class="pc-upload-content">
                                    <strong id="videoFileName">
                                        Pilih video kerusakan
                                    </strong>

                                    <small>
                                        MP4, WebM, atau MOV · Maksimal 20 MB
                                    </small>
                                </span>

                                <span class="pc-upload-action">
                                    Pilih
                                </span>
                            </label>

                            <input
                                type="file"
                                class="pc-file-input"
                                id="damage_video"
                                name="damage_video"
                                accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov">
                        </div>
                    </section>

                    <section class="pc-section">
                        <div class="pc-section-heading">
                            <div class="pc-section-icon">
                                <i class="bi bi-truck"></i>
                            </div>

                            <div>
                                <h2>Metode Penyerahan Barang</h2>
                                <p>Pilih bagaimana barang akan diserahkan untuk proses klaim.</p>
                            </div>
                        </div>

                        <div class="pc-field">
                            <label class="pc-label">
                                Pilih Metode
                                <span class="pc-required">*</span>
                            </label>

                            <div class="pc-choice-group">

                                <label class="pc-choice">
                                    <input
                                        type="radio"
                                        name="handover_method"
                                        value="Kurir"
                                        <?= old('handover_method') === 'Kurir' ? 'checked' : '' ?>
                                        required>

                                    <span class="pc-choice-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </span>

                                    <span class="pc-choice-copy">
                                        <strong>Kurir</strong>
                                        <small>Barang dikirim melalui kurir.</small>
                                    </span>

                                    <span class="pc-choice-check">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                </label>

                                <label class="pc-choice">
                                    <input
                                        type="radio"
                                        name="handover_method"
                                        value="Antar Langsung"
                                        <?= old('handover_method') === 'Antar Langsung' ? 'checked' : '' ?>>

                                    <span class="pc-choice-icon">
                                        <i class="bi bi-shop"></i>
                                    </span>

                                    <span class="pc-choice-copy">
                                        <strong>Antar Langsung</strong>
                                        <small>Barang diserahkan langsung.</small>
                                    </span>

                                    <span class="pc-choice-check">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                </label>

                            </div>
                        </div>

                        <div
                            class="pc-field"
                            id="shippingAddressField"
                            hidden>

                            <label for="shipping_address" class="pc-label">
                                Alamat Pengiriman
                                <span class="pc-required">*</span>
                            </label>

                            <textarea
                                class="pc-input pc-textarea pc-textarea-small"
                                id="shipping_address"
                                name="shipping_address"
                                maxlength="2000"
                                placeholder="Masukkan alamat lengkap untuk pengiriman barang..."><?= esc(old('shipping_address')) ?></textarea>

                            <div class="pc-help">
                                Wajib diisi jika memilih metode Kurir.
                            </div>
                        </div>
                    </section>

                    <section class="pc-terms">
                        <label class="pc-check">
                            <input
                                type="checkbox"
                                id="terms_agreed"
                                name="terms_agreed"
                                value="1"
                                <?= old('terms_agreed') === '1' ? 'checked' : '' ?>
                                required>

                            <span class="pc-check-box">
                                <i class="bi bi-check"></i>
                            </span>

                            <span class="pc-check-copy">
                                Saya menyatakan bahwa informasi dan bukti yang diberikan
                                adalah benar serta bersedia mengikuti syarat dan ketentuan
                                proses klaim garansi AIMpro Care.
                                <span class="pc-required">*</span>
                            </span>
                        </label>
                    </section>

                    <button
                        type="submit"
                        class="pc-submit"
                        id="submitClaimButton">

                        <i class="bi bi-send"></i>

                        <span>
                            Ajukan Klaim Garansi
                        </span>
                    </button>

                </form>
            </div>
        </section>

        <footer class="pc-footer">
            &copy; <?= date('Y') ?> AIMpro Care.
            After-Sales & Quality Control.
        </footer>

    </div>
</main>

<script src="<?= base_url('assets/js/public-claim.js') ?>" defer></script>

<?= $this->endSection() ?>