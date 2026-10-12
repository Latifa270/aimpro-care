<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ReportService;
use Throwable;

class ReportController extends BaseController
{
    protected ReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');

        $report = $this->reportService->getReport($startDate, $endDate);

        return view('admin/reports/index', [
            'title'     => 'Laporan',
            'heading'   => 'Laporan',
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'report'    => $report,
        ]);
    }

    public function exportExcel()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $report = $this->reportService->getReport($startDate, $endDate);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        $primaryColor = '22396F';

        $titleStyle = [
            'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => $primaryColor]],
            'alignment' => ['horizontal' => 'center'],
        ];

        $subtitleStyle = [
            'font' => ['size' => 11, 'color' => ['rgb' => '53657D']],
            'alignment' => ['horizontal' => 'center'],
        ];

        $headerInfoStyle = [
            'font' => ['size' => 10, 'color' => ['rgb' => '53657D']],
            'alignment' => ['horizontal' => 'center'],
        ];

        $sectionStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => $primaryColor]],
        ];

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $primaryColor],
            ],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];

        $dataStyle = [
            'alignment' => ['vertical' => 'center'],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']],
            ],
        ];

        $numberStyle = [
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']],
            ],
        ];

        $sheet->setCellValue('A1', 'CV. AMINS PROJECT TEKNOLOGI INDONESIA');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->applyFromArray($titleStyle);

        $sheet->setCellValue('A2', 'AIMPRO CARE — After-Sales & Quality Control System');
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->applyFromArray($subtitleStyle);

        $sheet->setCellValue('A3', 'Sistem Pendataan Unit, Quality Control, Aktivasi Garansi, dan Layanan Klaim');
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A3')->applyFromArray($headerInfoStyle);

        $sheet->setCellValue('A4', 'Website: aimprocare.aminsproject.com | Email: info@aminsproject.com');
        $sheet->mergeCells('A4:E4');
        $sheet->getStyle('A4')->applyFromArray($headerInfoStyle);

        $sheet->setCellValue('A6', 'LAPORAN PERIODE');
        $sheet->mergeCells('A6:E6');
        $sheet->getStyle('A6')->applyFromArray($sectionStyle);

        $sheet->setCellValue('A7', 'Periode: ' . $startDate . ' s.d. ' . $endDate);
        $sheet->mergeCells('A7:E7');
        $sheet->getStyle('A7')->applyFromArray($subtitleStyle);

        $row = 9;

        $sheet->setCellValue('A' . $row, 'RINGKASAN');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray($sectionStyle);
        $row++;

        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Kategori');
        $sheet->setCellValue('C' . $row, 'Jumlah');
        $sheet->mergeCells('C' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($headerStyle);
        $row++;

        $summary = [
            'Total Unit'       => $report['units']['total'] ?? 0,
            'Siap Dijual'      => $report['units']['ready_for_sale'] ?? 0,
            'Terjual'          => $report['units']['sold'] ?? 0,
            'Garansi Aktif'    => $report['warranties']['active'] ?? 0,
            'Garansi Berakhir' => $report['warranties']['expired'] ?? 0,
            'Total Klaim'      => $report['claims']['total'] ?? 0,
            'Klaim Selesai'    => $report['claims']['completed'] ?? 0,
        ];

        $no = 1;
        foreach ($summary as $label => $value) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $label);
            $sheet->setCellValue('C' . $row, $value);
            $sheet->mergeCells('C' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray($numberStyle);
            $sheet->getStyle('B' . $row . ':E' . $row)->applyFromArray($dataStyle);
            $row++;
            $no++;
        }

        $row += 2;
        $sheet->setCellValue('A' . $row, 'REKAP QC');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray($sectionStyle);
        $row++;

        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Hasil');
        $sheet->setCellValue('C' . $row, 'Jumlah');
        $sheet->mergeCells('C' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($headerStyle);
        $row++;

        $qcData = [
            'Lolos QC'                => $report['qc']['passed'] ?? 0,
            'Tidak Lolos QC'          => $report['qc']['failed'] ?? 0,
            'Perlu Pemeriksaan Ulang' => $report['qc']['reinspection'] ?? 0,
        ];

        $no = 1;
        foreach ($qcData as $label => $value) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $label);
            $sheet->setCellValue('C' . $row, $value);
            $sheet->mergeCells('C' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray($numberStyle);
            $sheet->getStyle('B' . $row . ':E' . $row)->applyFromArray($dataStyle);
            $row++;
            $no++;
        }

        $row += 2;
        $sheet->setCellValue('A' . $row, 'KLAIM PER STATUS');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray($sectionStyle);
        $row++;

        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Status');
        $sheet->setCellValue('C' . $row, 'Jumlah');
        $sheet->mergeCells('C' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($headerStyle);
        $row++;

        $no = 1;
        foreach ($report['claims_by_status'] as $status => $count) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $status);
            $sheet->setCellValue('C' . $row, $count);
            $sheet->mergeCells('C' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray($numberStyle);
            $sheet->getStyle('B' . $row . ':E' . $row)->applyFromArray($dataStyle);
            $row++;
            $no++;
        }

        $row += 2;
        $sheet->setCellValue('A' . $row, 'Dicetak pada: ' . date('d M Y H:i'));
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray($subtitleStyle);

        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        $filename = 'laporan-aimpro-' . date('Ymd-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');

        $report = $this->reportService->getReport($startDate, $endDate);

        $html = view('admin/reports/pdf', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'report'    => $report,
        ]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('laporan-aimpro-' . date('Ymd-His') . '.pdf', [
            'Attachment' => true,
        ]);
        exit;
    }
}
