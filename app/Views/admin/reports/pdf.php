<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #22396F;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .kop h1 {
            margin: 0;
            font-size: 20px;
            color: #22396F;
            letter-spacing: 1px;
        }

        .kop h2 {
            margin: 6px 0 0;
            font-size: 13px;
            color: #22396F;
            font-weight: bold;
        }

        .kop .subtitle {
            margin: 4px 0 0;
            font-size: 10px;
            color: #53657D;
        }

        .kop .contact {
            margin: 6px 0 0;
            font-size: 9px;
            color: #8290A3;
        }

        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h3 {
            margin: 0;
            font-size: 14px;
            color: #22396F;
            letter-spacing: 0.5px;
        }

        .report-title .period {
            margin-top: 6px;
            font-size: 11px;
            color: #53657D;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #22396F;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #C8DFDB;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        th {
            background: #22396F;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        td.no {
            text-align: center;
            width: 40px;
            color: #8290A3;
        }

        td.value {
            text-align: right;
            font-weight: bold;
            color: #22396F;
            width: 120px;
            padding-right: 40px;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 9px;
            color: #8290A3;
        }
    </style>
</head>

<body>

    <div class="kop">
        <h1>CV. AMINS PROJECT TEKNOLOGI INDONESIA</h1>
        <h2>AIMPRO CARE</h2>
        <div class="subtitle">
            After-Sales & Quality Control System
        </div>
        <div class="subtitle">
            Sistem Pendataan Unit, Quality Control, Aktivasi Garansi, dan Layanan Klaim
        </div>
        <div class="contact">
            Website: aimprocare.aminsproject.com | Email: info@aminsproject.com
        </div>
    </div>

    <div class="report-title">
        <h3>LAPORAN PERIODE</h3>
        <div class="period">
            Periode: <?= esc($startDate) ?> s.d. <?= esc($endDate) ?>
        </div>
    </div>

    <div class="section">
        <div class="section-title">RINGKASAN</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Kategori</th>
                    <th style="text-align: right; width: 100px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="no">1</td>
                    <td>Total Unit</td>
                    <td class="value"><?= number_format($report['units']['total'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">2</td>
                    <td>Siap Dijual</td>
                    <td class="value"><?= number_format($report['units']['ready_for_sale'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">3</td>
                    <td>Terjual</td>
                    <td class="value"><?= number_format($report['units']['sold'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">4</td>
                    <td>Garansi Aktif</td>
                    <td class="value"><?= number_format($report['warranties']['active'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">5</td>
                    <td>Garansi Berakhir</td>
                    <td class="value"><?= number_format($report['warranties']['expired'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">6</td>
                    <td>Total Klaim</td>
                    <td class="value"><?= number_format($report['claims']['total'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">7</td>
                    <td>Klaim Selesai</td>
                    <td class="value"><?= number_format($report['claims']['completed'] ?? 0) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">REKAP QC</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Hasil</th>
                    <th style="text-align: right; width: 100px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="no">1</td>
                    <td>Lolos QC</td>
                    <td class="value"><?= number_format($report['qc']['passed'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">2</td>
                    <td>Tidak Lolos QC</td>
                    <td class="value"><?= number_format($report['qc']['failed'] ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="no">3</td>
                    <td>Perlu Pemeriksaan Ulang</td>
                    <td class="value"><?= number_format($report['qc']['reinspection'] ?? 0) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">KLAIM PER STATUS</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Status</th>
                    <th style="text-align: right; width: 100px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['claims_by_status'])): ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #8290A3;">
                            Belum ada data klaim.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($report['claims_by_status'] as $status => $count): ?>
                        <tr>
                            <td class="no"><?= $no ?></td>
                            <td><?= esc($status) ?></td>
                            <td class="value"><?= number_format($count) ?></td>
                        </tr>
                        <?php $no++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak pada <?= date('d M Y H:i') ?> — AIMpro Care
    </div>

</body>

</html>