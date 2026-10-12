<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/public-claim.css') ?>">

<?php
$claimId = $claimId ?? null;
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

        <section class="pc-card pc-success-card">

            <div class="pc-success-content">

                <div class="pc-success-icon" aria-hidden="true">
                    <i class="bi bi-check-lg"></i>
                </div>

                <span class="pc-eyebrow">
                    <i class="bi bi-patch-check"></i>
                    Pengajuan Berhasil
                </span>

                <h1 class="pc-title">
                    Klaim Garansi Berhasil Diajukan
                </h1>

                <p class="pc-description">
                    Pengajuan klaim garansi Anda telah berhasil diterima
                    oleh AIMpro Care dan akan diproses oleh tim kami.
                </p>

                <?php if ($claimId): ?>
                    <div class="pc-success-reference">
                        <span class="pc-success-reference-label">
                            Referensi Pengajuan
                        </span>

                        <strong>
                            #<?= esc($claimId) ?>
                        </strong>
                    </div>
                <?php endif; ?>

                <div class="pc-success-info">
                    <div class="pc-success-info-item">
                        <div class="pc-success-info-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>

                        <div>
                            <strong>Pengajuan diterima</strong>
                            <p>
                                Data dan bukti klaim telah diterima
                                untuk proses pemeriksaan.
                            </p>
                        </div>
                    </div>

                    <div class="pc-success-info-item">
                        <div class="pc-success-info-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                        <div>
                            <strong>Menunggu proses</strong>
                            <p>
                                Tim AIMpro Care akan memproses klaim
                                sesuai tahapan pemeriksaan yang berlaku.
                            </p>
                        </div>
                    </div>

                    <div class="pc-success-info-item">
                        <div class="pc-success-info-icon">
                            <i class="bi bi-whatsapp"></i>
                        </div>

                        <div>
                            <strong>Pantau informasi klaim</strong>
                            <p>
                                Pastikan nomor WhatsApp yang didaftarkan
                                tetap dapat dihubungi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pc-success-actions">
                    <a
                        href="<?= base_url('cek-garansi') ?>"
                        class="pc-submit pc-submit-link">
                        <i class="bi bi-shield-check"></i>
                        <span>Kembali ke Cek Garansi</span>
                    </a>
                </div>

            </div>

        </section>

        <footer class="pc-footer">
            &copy; <?= date('Y') ?> AIMpro Care.
            After-Sales & Quality Control.
        </footer>

    </div>
</main>

<?= $this->endSection() ?>