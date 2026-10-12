<?php

namespace App\Services;

use Config\Wakita as WakitaConfig;
use RuntimeException;
use Throwable;

class WakitaService
{
    private WakitaConfig $config;

    public function __construct()
    {
        $this->config = config(WakitaConfig::class);
    }

    public function checkStatus(): array
    {
        return $this->request('GET', '/status');
    }

    public function sendMessage(
        string $destination,
        string $message
    ): array {
        $number = $this->normalizeNumber($destination);

        if ($number === '') {
            throw new RuntimeException('Nomor tujuan tidak valid.');
        }

        if (trim($message) === '') {
            throw new RuntimeException('Pesan tidak boleh kosong.');
        }

        $response = $this->request('POST', '/send', [
            'number'  => $number,
            'message' => $message,
        ]);

        if (! ($response['body']['success'] ?? false)) {
            $errorMessage = $response['body']['message']
                ?? 'Gateway WAKITA gagal mengirim pesan.';

            throw new RuntimeException($errorMessage);
        }

        return [
            'http_status' => $response['status'],
            'outbox_id'   => $response['body']['data']['outbox_id'] ?? null,
            'body'        => $response['body'],
        ];
    }

    public function checkMessageStatus(string $outboxId): array
    {
        return $this->request('GET', '/message-status/' . rawurlencode($outboxId));
    }

    private function request(
        string $method,
        string $endpoint,
        array $data = []
    ): array {
        if (
            trim($this->config->baseUrl) === ''
            || trim($this->config->apiKey) === ''
        ) {
            throw new RuntimeException(
                'Konfigurasi gateway WAKITA belum lengkap.'
            );
        }

        $client = \Config\Services::curlrequest([
            'baseURI' => rtrim($this->config->baseUrl, '/') . '/',
            'timeout' => $this->config->timeout,
            'http_errors' => false,
        ]);

        $headers = [
            'X-API-Key'    => $this->config->apiKey,
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if (! empty($this->config->apiSecret)) {
            $headers['X-API-Secret'] = $this->config->apiSecret;
        }

        $options = [
            'headers' => $headers,
        ];

        if ($method !== 'GET') {
            $options['json'] = $data;
        }

        $response = $client->request($method, ltrim($endpoint, '/'), $options);

        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true) ?? [];

        return [
            'status' => $statusCode,
            'body'   => $body,
        ];
    }

    private function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }
}
