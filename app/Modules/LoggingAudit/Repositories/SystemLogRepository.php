<?php

/**
 * @file SystemLogRepository.php
 * @description مستودع السجلات النظامية — يكتب ويقرأ من جدول system_logs
 * @module LoggingAudit
 * @author Team Leader (Khalid)
 */

namespace App\Modules\LoggingAudit\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\LoggingAudit\Models\SystemLog;

class SystemLogRepository extends BaseRepository
{
    public function __construct(SystemLog $model)
    {
        parent::__construct($model);
    }

    /**
     * كتابة سجل نظام جديد
     */
    public function writeLog(array $data): SystemLog
    {
        $data['created_at'] = now();
        return $this->model->create($data);
    }

    /**
     * جلب الأخطاء الحرجة (level = error | critical)
     */
    public function getErrors(int $perPage = 50)
    {
        return $this->model->errors()->latest('created_at')->paginate($perPage);
    }

    /**
     * جلب سجلات بقناة معينة
     */
    public function getByChannel(string $channel, int $perPage = 50)
    {
        return $this->model->byChannel($channel)->latest('created_at')->paginate($perPage);
    }

    /**
     * جلب سجلات بمستوى معين
     */
    public function getByLevel(string $level, int $perPage = 50)
    {
        return $this->model->byLevel($level)->latest('created_at')->paginate($perPage);
    }

    /**
     * حذف السجلات القديمة (Data Retention Policy)
     */
    public function deleteOlderThan(int $retentionDays): int
    {
        $cutoff = now()->subDays($retentionDays);
        return $this->model->where('created_at', '<', $cutoff)->delete();
    }

    /**
     * جلب سجلات مستخدم معين
     */
    public function getForUser(int $userId, int $perPage = 50)
    {
        return $this->model->where('user_id', $userId)->latest('created_at')->paginate($perPage);
    }

    /**
     * جلب سجلات correlation_id معين (تتبع request كامل)
     */
    public function getByCorrelationId(string $correlationId)
    {
        return $this->model->where('correlation_id', $correlationId)->latest('created_at')->get();
    }
}
