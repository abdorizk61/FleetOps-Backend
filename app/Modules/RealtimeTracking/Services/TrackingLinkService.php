<?php

/**
 * @file: TrackingLinkService.php
 * @description: خدمة روابط التتبع العامة للعملاء (RT-01 / fn33)
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Services;

use App\Modules\RealtimeTracking\Repositories\TrackingLinkRepository;
use Illuminate\Support\Str;
use Exception;

class TrackingLinkService
{
    protected TrackingLinkRepository $trackingLinkRepository;

    public function __construct(TrackingLinkRepository $trackingLinkRepository)
    {
        $this->trackingLinkRepository = $trackingLinkRepository;
    }

    /**
     * إنشاء رابط تتبع موقّع لطلب معين (RT-01)
     * @param int $orderId
     * @param array $options (customer_phone, customer_email, expires_hours)
     * @return array  ['url' => string, 'token' => string, 'expires_at' => datetime]
     * @throws Exception
     */
    public function generateTrackingLink(int $orderId, array $options = []): array
    {
        // TODO: Generate tracking link
        // 1. Check if active link already exists for order → deactivate it
        // 2. Generate unique token: Str::random(64)
        // 3. Set expiry: now()->addHours($options['expires_hours'] ?? 48)
        // 4. Create record in tracking_links table
        // 5. Build signed URL: URL::temporarySignedRoute('tracking.public', expiry, ['token' => $token])
        // 6. Return ['url' => $url, 'token' => $token, 'expires_at' => $expiry]
    }

    /**
     * جلب بيانات التتبع العامة (بدون PII) (RT-01)
     * @param string $token
     * @return array
     * @throws Exception
     */
    public function getPublicTrackingData(string $token): array
    {
        // TODO: Get public tracking data
        // 1. Find link by token: $link = $this->trackingLinkRepository->findByToken($token)
        // 2. Validate: $link->isValid() → throw Exception('الرابط منتهي أو غير صحيح')
        // 3. Increment access count
        // 4. Get last GPS location for the order's driver (masked - no PII)
        // 5. Get order status timeline
        // 6. Return public-safe data (hide driver name, phone, etc.)
    }
}
