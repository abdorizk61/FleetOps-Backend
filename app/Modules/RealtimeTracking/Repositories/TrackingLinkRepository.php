<?php

/**
 * @file: TrackingLinkRepository.php
 * @description: مستودع بيانات روابط التتبع - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\RealtimeTracking\Models\TrackingLink;

class TrackingLinkRepository extends BaseRepository
{
    public function __construct(TrackingLink $model)
    {
        parent::__construct($model);
    }

    /**
     * البحث عن رابط تتبع بالـ token
     * @param string $token
     * @return TrackingLink|null
     */
    public function findByToken(string $token): ?TrackingLink
    {
        // TODO: return $this->model->where('token', $token)->first();
    }

    /**
     * البحث عن رابط نشط لطلب معين
     * @param int $orderId
     * @return TrackingLink|null
     */
    public function findActiveByOrder(int $orderId): ?TrackingLink
    {
        // TODO: return $this->model->active()->where('order_id', $orderId)->first();
    }

    /**
     * تسجيل وصول لرابط التتبع (زيادة عداد الوصول)
     * @param int $linkId
     * @return bool
     */
    public function incrementAccessCount(int $linkId): bool
    {
        // TODO: $this->model->where('link_id', $linkId)->increment('access_count');
        // return true;
    }

    /**
     * إلغاء تفعيل رابط التتبع
     * @param int $linkId
     * @return bool
     */
    public function deactivate(int $linkId): bool
    {
        // TODO: return $this->update($linkId, ['is_active' => false]);
    }
}
