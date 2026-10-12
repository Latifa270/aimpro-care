<?php

namespace App\Libraries;

use App\Models\ActivityLogModel;

class ActivityLogger
{
    public static function log(
        string $action,
        ?string $description = null
    ): bool {
        $userId = session()->get('user_id');

        if (empty($userId)) {
            log_message(
                'warning',
                'Activity Log gagal: user_id tidak ditemukan di session.'
            );

            return false;
        }

        try {
            $activityLogModel = new ActivityLogModel();

            return (bool) $activityLogModel->insert([
                'user_id'     => $userId,
                'action'      => $action,
                'description' => $description,
                'ip_address'  => service('request')->getIPAddress(),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message(
                'error',
                'Gagal menyimpan Activity Log: ' . $e->getMessage()
            );

            return false;
        }
    }
}
