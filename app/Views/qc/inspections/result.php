<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <!-- ===== PAGE HEADER ===== -->
    <div class="app-page-header">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <span class="app-page-label">
                    <i class="bi bi-check2-square"></i>
                    QUALITY CONTROL
                </span>

                <h1 class="app-page-title">Hasil QC</h1>

                <p class="app-page-description">
                    Tentukan hasil akhir pemeriksaan unit.
                </p>
            </div>

            <div class="app-page-icon">
                <i class="bi bi-check2-square"></i>
            </div>
        </div>
    </div>

    <!-- ===== FLASH ===== -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="app-alert app-alert-danger mb-4">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="app-alert app-alert-success mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <!-- ===== RINGKASAN PEMERIKSAAN ===== -->
    <div class="app-card mb-4">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Ringkasan Pemeriksaan</h2>
                <p class="app-card-subtitle mb-0">
                    Data unit yang diperiksa.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-info-circle"></i>
            </div>
        </div>

        <div class="app-card-body">
            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">PRODUK</span>
                    <div class="fw-semibold">
                        <?= esc($inspection['product_name']) ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <span class="app-unit-label">SERIAL NUMBER</span>
                    <div class="fw-semibold">
                        <?= esc($inspection['serial_number']) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ===== RINGKASAN CHECKLIST ===== -->
    <div class="app-card mb-4">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Ringkasan Checklist</h2>
                <p class="app-card-subtitle mb-0">
                    Hasil pemeriksaan 7 item checklist.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-list-check"></i>
            </div>
        </div>

        <div class="app-card-body">

            <?php if ($hasFailedChecklist): ?>
                <div class="app-alert app-alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <div class="fw-semibold mb-1">
                            Ditemukan checklist yang tidak memenuhi kriteria.
                        </div>
                        <small>
                            Silakan evaluasi hasil pemeriksaan sebelum menentukan
                            keputusan akhir QC. Jika unit tetap dinyatakan Lolos QC,
                            alasan keputusan wajib dicantumkan.
                        </small>
                    </div>
                </div>
            <?php else: ?>
                <div class="app-alert app-alert-success mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>
                        <div class="fw-semibold">
                            Seluruh checklist memenuhi kriteria.
                        </div>
                        <small>
                            Tidak ditemukan checklist yang gagal.
                        </small>
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-flex flex-column gap-2">
                <?php foreach ($checklist as $index => $item): ?>
                    <?php
                    $isPassed = (
                        $item['result'] === true
                        || $item['result'] === 1
                        || $item['result'] === '1'
                    );
                    ?>

                    <div class="app-card">
                        <div class="app-card-body d-flex justify-content-between align-items-center gap-3">
                            <div class="fw-semibold">
                                <?= esc($index + 1) ?>.
                                <?= esc($item['item_name']) ?>
                            </div>

                            <?php if ($isPassed): ?>
                                <span class="app-badge app-badge-success">Ya</span>
                            <?php else: ?>
                                <span class="app-badge app-badge-danger">Tidak</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <!-- ===== FORM HASIL QC ===== -->
    <div class="app-card">
        <div class="app-card-header">
            <div>
                <h2 class="app-card-title">Pilih Hasil Pemeriksaan</h2>
                <p class="app-card-subtitle mb-0">
                    Tentukan hasil akhir QC untuk unit ini.
                </p>
            </div>

            <div class="app-stat-icon app-stat-icon-primary">
                <i class="bi bi-clipboard-check"></i>
            </div>
        </div>

        <div class="app-card-body">
            <form
                action="<?= site_url('qc/inspections/' . $inspection['id'] . '/result') ?>"
                method="post"
                data-has-failed="<?= $hasFailedChecklist ? 'true' : 'false' ?>">

                <?= csrf_field() ?>

                <div class="d-flex flex-column gap-3">

                    <label class="app-card">
                        <div class="app-card-body d-flex gap-3 align-items-start">
                            <input
                                class="form-check-input mt-1"
                                type="radio"
                                name="result"
                                value="Lolos QC"
                                <?= old('result') === 'Lolos QC' ? 'checked' : '' ?>
                                required>

                            <div>
                                <div class="fw-semibold">Lolos QC</div>
                                <small class="text-muted">
                                    Unit memenuhi kriteria pemeriksaan dan siap dijual.
                                </small>
                            </div>
                        </div>
                    </label>

                    <label class="app-card">
                        <div class="app-card-body d-flex gap-3 align-items-start">
                            <input
                                class="form-check-input mt-1"
                                type="radio"
                                name="result"
                                value="Tidak Lolos QC"
                                <?= old('result', $inspection['result']) === 'Tidak Lolos QC' ? 'checked' : '' ?>>

                            <div>
                                <div class="fw-semibold">Tidak Lolos QC</div>
                                <small class="text-muted">
                                    Unit tidak memenuhi kriteria kelayakan.
                                </small>
                            </div>
                        </div>
                    </label>

                    <label class="app-card">
                        <div class="app-card-body d-flex gap-3 align-items-start">
                            <input
                                class="form-check-input mt-1"
                                type="radio"
                                name="result"
                                value="Perlu Pemeriksaan Ulang"
                                <?= old('result', $inspection['result']) === 'Perlu Pemeriksaan Ulang' ? 'checked' : '' ?>>

                            <div>
                                <div class="fw-semibold">Perlu Pemeriksaan Ulang</div>
                                <small class="text-muted">
                                    Unit membutuhkan evaluasi atau pemeriksaan tambahan.
                                </small>
                            </div>
                        </div>
                    </label>

                </div>

                <div
                    id="decisionReasonWrapper"
                    class="mt-4"
                    style="<?= $hasFailedChecklist && old('result', $inspection['result']) === 'Lolos QC' ? '' : 'display: none;' ?>">

                    <label for="notes" class="form-label">
                        Alasan Keputusan QC <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        class="form-control"
                        rows="4"
                        maxlength="5000"
                        placeholder="Jelaskan alasan unit tetap dinyatakan Lolos QC meskipun terdapat checklist yang tidak memenuhi kriteria."
                        <?= $hasFailedChecklist && old('result', $inspection['result']) === 'Lolos QC' ? 'required' : '' ?>><?= esc(old('notes', $inspection['notes'] ?? '')) ?></textarea>

                    <div class="form-text">
                        Alasan wajib diisi karena terdapat checklist yang gagal.
                    </div>

                </div>

                <div class="d-flex flex-column flex-sm-row gap-2 mt-4">

                    <a
                        href="<?= site_url('qc/inspections/' . $inspection['id'] . '/checklist') ?>"
                        class="btn-app-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>

                    <button
                        type="submit"
                        class="btn-app-primary">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Hasil QC</span>
                    </button>

                </div>

            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/qc-result.js') ?>" defer></script>
<?= $this->endSection() ?>