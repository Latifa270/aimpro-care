<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cek Garansi - AIMpro Care</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/pages/public-warranty.css') ?>">
</head>

<body>

    <?php
    $result = $result ?? null;
    $keyword = $keyword ?? '';
    $notFound = $notFound ?? false;
    $error = $error ?? null;

    $productParts = [];

    if ($result) {
        $productParts = array_filter([
            $result['product_brand'] ?? null,
            $result['product_name'] ?? null,
            $result['product_model'] ?? null,
        ], static fn($value) => trim((string) $value) !== '');
    }

    $productName = implode(' ', $productParts);

    $formatDate = static function ($date): string {
        if (empty($date)) {
            return '-';
        }

        $timestamp = strtotime($date);

        return $timestamp
            ? date('d M Y', $timestamp)
            : '-';
    };

    $isActive = $result
        && ! ($result['is_expired'] ?? false)
        && strtolower((string) ($result['warranty_status'] ?? '')) === 'aktif';
    ?>

    <div class="pw-shell">

        <header class="pw-header">
            <a href="<?= site_url('cek-garansi') ?>" class="pw-brand">
                <span class="pw-brand-icon">A</span>

                <span>
                    <strong>AIMpro Care</strong>
                    <small>Product Protection</small>
                </span>
            </a>

            <span class="pw-header-label">
                Warranty Verification
            </span>
        </header>

        <main class="pw-main">

            <section class="pw-intro">
                <div class="pw-eyebrow">
                    <span class="pw-eyebrow-dot"></span>
                    LAYANAN PELANGGAN
                </div>

                <h1>
                    Perlindungan produk,
                    <span>lebih tenang.</span>
                </h1>

                <p>
                    Periksa status garansi produk AIMpro Care dengan
                    memasukkan nomor garansi atau nomor seri produk.
                </p>
            </section>

            <section class="pw-search-card">
                <form action="<?= site_url('cek-garansi') ?>" method="post">
                    <?= csrf_field() ?>

                    <label for="pwKeyword">
                        Nomor Garansi atau Nomor Seri
                    </label>

                    <div class="pw-search-control">
                        <span class="pw-search-icon" aria-hidden="true">⌕</span>

                        <input
                            type="text"
                            id="pwKeyword"
                            name="keyword"
                            value="<?= esc($keyword) ?>"
                            maxlength="100"
                            placeholder="Contoh: WRT-2026-0001"
                            autocomplete="off"
                            required>

                        <button type="submit">
                            Periksa
                            <span aria-hidden="true">→</span>
                        </button>
                    </div>

                    <small>
                        Masukkan nomor yang tertera pada kartu garansi atau label produk.
                    </small>
                </form>
            </section>

            <?php if ($error): ?>
                <div class="pw-alert pw-alert-error" role="alert">
                    <?= esc($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($notFound): ?>
                <section class="pw-result-card pw-empty-state">
                    <div class="pw-empty-icon">?</div>

                    <h2>Garansi tidak ditemukan</h2>

                    <p>
                        Periksa kembali nomor yang kamu masukkan.
                        Pastikan tidak ada kesalahan penulisan.
                    </p>
                </section>
            <?php endif; ?>

            <?php if ($result): ?>

                <section class="pw-warranty-card">

                    <div class="pw-card-top">
                        <div>
                            <span class="pw-card-kicker">
                                DIGITAL WARRANTY CARD
                            </span>

                            <h2>AIMpro Care</h2>

                            <p>Perlindungan resmi produk Anda</p>
                        </div>

                        <div class="pw-card-emblem" aria-hidden="true">
                            ✓
                        </div>
                    </div>

                    <div class="pw-card-body">

                        <div class="pw-card-product">
                            <span class="pw-label">PRODUK TERDAFTAR</span>

                            <h3>
                                <?= esc($productName ?: 'Produk AIMpro Care') ?>
                            </h3>

                            <p>
                                Nomor Seri:
                                <strong><?= esc($result['serial_number'] ?? '-') ?></strong>
                            </p>
                        </div>

                        <div class="pw-warranty-number">
                            <span class="pw-label">NOMOR GARANSI</span>

                            <strong>
                                <?= esc($result['warranty_number'] ?? '-') ?>
                            </strong>
                        </div>

                        <div class="pw-card-divider"></div>

                        <div class="pw-card-grid">

                            <div>
                                <span class="pw-label">PEMILIK GARANSI</span>
                                <strong>
                                    <?= esc($result['customer_name'] ?? '-') ?>
                                </strong>
                            </div>

                            <div>
                                <span class="pw-label">TANGGAL PEMBELIAN</span>
                                <strong>
                                    <?= esc($formatDate($result['purchase_date'] ?? null)) ?>
                                </strong>
                            </div>

                            <div>
                                <span class="pw-label">GARANSI DIMULAI</span>
                                <strong>
                                    <?= esc($formatDate($result['start_date'] ?? null)) ?>
                                </strong>
                            </div>

                            <div>
                                <span class="pw-label">GARANSI BERAKHIR</span>
                                <strong>
                                    <?= esc($formatDate($result['end_date'] ?? null)) ?>
                                </strong>
                            </div>

                            <div>
                                <span class="pw-label">DURASI</span>
                                <strong>
                                    <?= esc((string) ($result['duration_months'] ?? '-')) ?>
                                    bulan
                                </strong>
                            </div>

                            <div>
                                <span class="pw-label">STATUS GARANSI</span>

                                <strong class="pw-status <?= $isActive ? 'is-active' : 'is-expired' ?>">
                                    <span></span>
                                    <?= esc($result['display_status'] ?? '-') ?>
                                </strong>
                            </div>

                        </div>
                    </div>

                    <div class="pw-card-footer">
                        <span>Verified by AIMpro Care</span>
                        <span>Digital Protection</span>
                    </div>

                </section>

                <section class="pw-verification-note <?= $isActive ? 'is-success' : 'is-warning' ?>">
                    <div class="pw-verification-icon">
                        <?= $isActive ? '✓' : '!' ?>
                    </div>

                    <div>
                        <strong>
                            <?= $isActive
                                ? 'Garansi terdaftar'
                                : 'Informasi status garansi'
                            ?>
                        </strong>

                        <p>
                            <?= $isActive
                                ? 'Data garansi ini tercatat dalam sistem AIMpro Care. Simpan informasi ini sebagai referensi.'
                                : 'Data produk ditemukan. Silakan perhatikan status dan tanggal berakhir garansi di atas.'
                            ?>
                        </p>
                    </div>
                </section>

                <?php if (! empty($result['card_path'])): ?>
                    <div class="pw-actions">
                        <a
                            href="<?= base_url($result['card_path']) ?>"
                            class="pw-download-button"
                            target="_blank"
                            rel="noopener">
                            <span aria-hidden="true">↓</span>
                            Lihat / Unduh Kartu PDF
                        </a>

                        <button
                            type="button"
                            class="pw-print-button"
                            onclick="window.print()">
                            Cetak Halaman
                        </button>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </main>

        <footer class="pw-footer">
            <strong>AIMpro Care</strong>
            <span>© <?= date('Y') ?> · Product Protection & Warranty Service</span>
        </footer>

    </div>

    <script src="<?= base_url('assets/js/public-warranty.js') ?>" defer></script>

</body>

</html>