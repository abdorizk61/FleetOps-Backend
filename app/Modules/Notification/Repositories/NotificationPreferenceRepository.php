<?php

/**
 * @file: NotificationPreferenceRepository.php
 * @description: مستودع تفضيلات الإشعارات - Notification Service (NF-02)
 * @module: Notification
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Notification\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\Notification\Models\NotificationPreference;

class NotificationPreferenceRepository extends BaseRepository
{
    public function __construct(NotificationPreference $model)
    {
        parent::__construct($model);
    }

    /**
     * جلب تفضيلات مستخدم معين
     * @param int $userId
     * @return NotificationPreference|null
     */
    public function getForUser(int $userId): ?NotificationPreference
    {
        // TODO: return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Upsert تفضيلات المستخدم (Create or Update)
     * @param int $userId
     * @param array $data
     * @return NotificationPreference
     */
    public function upsertForUser(int $userId, array $data): NotificationPreference
    {
        // TODO: Upsert user preferences
        // return $this->model->updateOrCreate(
        //     ['user_id' => $userId],
        //     $data
        // );
    }

    /**
     * التحقق من ساعات الصمت (Quiet Hours)
     * @param int $userId
     * @return bool  true = currently in quiet hours
     */
    public function isInQuietHours(int $userId): bool
    {
        // TODO: Check if current time is within user's quiet hours
        // 1. Get preference: $pref = $this->getForUser($userId)
        // 2. If no quiet hours set → return false
        // 3. Parse quiet_hours_start and quiet_hours_end
        // 4. Compare with current time considering timezone
        // 5. Return true if current time is within quiet window
    }
}
