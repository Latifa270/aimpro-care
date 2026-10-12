<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLogController extends BaseController
{
    protected ActivityLogModel $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $logs = $this->activityLogModel
            ->select('activity_logs.*, users.name AS user_name, users.role AS user_role')
            ->join('users', 'users.id = activity_logs.user_id')
            ->orderBy('activity_logs.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'   => 'Activity Log',
            'heading' => 'Activity Log',
            'logs'    => $logs,
        ];

        return view('admin/activity_logs/index', $data);
    }
}
