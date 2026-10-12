<?php

namespace App\Commands;

use App\Services\NotificationService;
use App\Services\NotificationTemplateService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Throwable;

class WarrantyExpiryReminder extends BaseCommand
{
    protected $group = 'Warranty';
    protected $name = 'warranty:expiry-reminder';
    protected $description =
    'Mengirim pengingat WhatsApp untuk garansi yang akan berakhir.';

    private const REMINDER_DAYS = [30, 7, 1];

    public function run(array $params)
    {
        $db = Database::connect();

        $notificationService = new NotificationService();
        $templateService = new NotificationTemplateService();

        $today = new \DateTimeImmutable('today');

        $sent = 0;
        $failed = 0;
        $skipped = 0;

        foreach (self::REMINDER_DAYS as $days) {
            $targetDate = $today
                ->modify("+{$days} days")
                ->format('Y-m-d');

            CLI::write(
                "Memeriksa garansi berakhir {$days} hari lagi: {$targetDate}",
                'yellow'
            );

            $warranties = $db->table('warranties w')
                ->select(
                    'w.id AS warranty_id,
                     w.warranty_number,
                     w.end_date,
                     s.customer_id,
                     c.name AS customer_name,
                     c.whatsapp AS customer_whatsapp'
                )
                ->join('sales s', 's.id = w.sales_id')
                ->join('customers c', 'c.id = s.customer_id')
                ->where('w.end_date', $targetDate)
                ->get()
                ->getResultArray();

            foreach ($warranties as $warranty) {
                $template = "warranty_expiring_{$days}_days";

                $alreadySent = $db->table('notifications')
                    ->where('warranty_id', $warranty['warranty_id'])
                    ->where('template', $template)
                    ->where('status', 'Berhasil')
                    ->countAllResults() > 0;

                if ($alreadySent) {
                    $skipped++;
                    continue;
                }

                if (empty($warranty['customer_whatsapp'])) {
                    CLI::write(
                        "Nomor WhatsApp kosong untuk garansi "
                            . $warranty['warranty_number'],
                        'red'
                    );

                    $failed++;
                    continue;
                }

                $message = $templateService->warrantyExpiring([
                    'customer_name' => $warranty['customer_name'],
                    'warranty_number' => $warranty['warranty_number'],
                    'end_date' => $warranty['end_date'],
                    'days_remaining' => $days,
                ]);

                try {
                    $result = $notificationService->send(
                        $warranty['customer_whatsapp'],
                        $template,
                        $message,
                        (int) $warranty['warranty_id'],
                        null
                    );

                    if ($result['success']) {
                        $sent++;

                        CLI::write(
                            "Berhasil: {$warranty['warranty_number']}",
                            'green'
                        );
                    } else {
                        $failed++;

                        CLI::write(
                            "Gagal: {$warranty['warranty_number']}",
                            'red'
                        );
                    }
                } catch (Throwable $e) {

                    $failed++;

                    log_message(
                        'error',
                        'Expiry reminder error: ' . $e->getMessage()
                    );
                }
            }
        }

        CLI::newLine();
        CLI::write('Ringkasan pengingat garansi', 'yellow');
        CLI::write("Berhasil dikirim : {$sent}");
        CLI::write("Gagal            : {$failed}");
        CLI::write("Dilewati         : {$skipped}");
    }
}
