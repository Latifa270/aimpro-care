<?php

namespace App\Services;

use App\Models\SettingModel;
use RuntimeException;
use Throwable;

class SettingService
{
    protected SettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function getAll(): array
    {
        $rows = $this->settingModel->findAll();

        $settings = [];

        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }

        return $settings;
    }

    public function get(string $key, $default = null)
    {
        $row = $this->settingModel
            ->where('key', $key)
            ->first();

        return $row['value'] ?? $default;
    }

    public function updateAll(array $data): void
    {
        $db = db_connect();
        $db->transBegin();

        try {
            foreach ($data as $key => $value) {
                $this->setKey($key, $value);
            }

            if ($db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi pengaturan gagal.'
                );
            }

            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    private function setKey(string $key, $value): void
    {
        $existing = $this->settingModel
            ->where('key', $key)
            ->first();

        $now = date('Y-m-d H:i:s');

        if ($existing) {
            $this->settingModel->update($existing['id'], [
                'value'      => (string) $value,
                'updated_at' => $now,
            ]);
        } else {
            $this->settingModel->insert([
                'key'        => $key,
                'value'      => (string) $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
