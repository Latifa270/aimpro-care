<?php

namespace App\Services;

use Throwable;

class NotificationService
{
    private WakitaService $wakita;
    private NotificationLogService $logs;

    public function __construct()
    {
        $this->wakita = new WakitaService();
        $this->logs = new NotificationLogService();
    }

    public function send(
        string $destination,
        string $template,
        string $message,
        ?int $warrantyId = null,
        ?int $claimId = null
    ): array {
        $status = 'Gagal';
        $gatewayResponse = null;
        $error = null;
        $sentAt = null;

        try {
            $response = $this->wakita->sendMessage(
                $destination,
                $message
            );

            $status = 'Berhasil';
            $sentAt = date('Y-m-d H:i:s');
            $gatewayResponse = $response['body'] ?? null;
        } catch (Throwable $e) {
            $error = $e->getMessage();

            log_message(
                'error',
                'WAKITA send failed: ' . $error
            );

            $gatewayResponse = $error;
        }

        try {
            $logId = $this->logs->record([
                'warranty_id'       => $warrantyId,
                'warranty_claim_id' => $claimId,
                'destination'       => $destination,
                'template'          => $template,
                'sent_at'           => $sentAt,
                'status'            => $status,
                'gateway_response'  => $gatewayResponse,
            ]);

            return [
                'success' => $status === 'Berhasil',
                'log_id'  => $logId,
                'error'   => $error,
            ];
        } catch (Throwable $e) {
            log_message(
                'critical',
                'Notification log failed: ' . $e->getMessage()
            );

            return [
                'success' => $status === 'Berhasil',
                'log_id'  => null,
                'error'   => $error,
            ];
        }
    }
}
