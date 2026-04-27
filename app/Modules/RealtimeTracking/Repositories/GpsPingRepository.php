<?php

/**
 * @file: GpsPingRepository.php
 * @description: مستودع بيانات نبضات GPS - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\RealtimeTracking\Models\GpsPing;

class GpsPingRepository extends BaseRepository
{
    public function __construct(GpsPing $model)
    {
        parent::__construct($model);
    }

    /**
     * جلب آخر موقع معروف لسائق
     * @param int $driverId
     * @return GpsPing|null
     */
    public function getLastKnownLocation(int $driverId): ?GpsPing
    {
        // TODO: Return last ping for driver (not spoofed)
        // return $this->model->forDriver($driverId)->notSpoofed()->latest('recorded_at')->first();
    }

    /**
     * جلب مسار نبضات GPS لرحلة معينة
     * @param int $routeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRouteTrail(int $routeId)
    {
        // TODO: Return all GPS pings for a route ordered by time
        // return $this->model->where('route_id', $routeId)->orderBy('recorded_at')->get();
    }

    /**
     * التحقق من انقطاع السائق (Heartbeat Timeout)
     * @param int $driverId
     * @param int $minutes
     * @return bool  - true if driver is offline
     */
    public function isDriverOffline(int $driverId, int $minutes = 3): bool
    {
        // TODO: Check if no pings received in last N minutes
        // return !$this->model->forDriver($driverId)->recent($minutes)->exists();
    }

    /**
     * تسجيل نبضة GPS جديدة
     * @param array $data
     * @return GpsPing
     */
    public function recordPing(array $data): GpsPing
    {
        // TODO: Create a new GPS ping record
        // return $this->create($data);
    }

    /**
     * تحديد النبضات المشبوهة (GPS Spoofing - RT-06)
     * @param int $driverId
     * @param float $newLat
     * @param float $newLng
     * @param \DateTime $newTime
     * @return bool  - true if suspicious
     */
    public function detectSpoofing(int $driverId, float $newLat, float $newLng, \DateTime $newTime): bool
    {
        // TODO: Detect GPS spoofing
        // 1. Get last ping for driver
        // 2. Calculate distance between last and new ping
        // 3. Calculate time difference
        // 4. Calculate implied speed = distance / time
        // 5. If speed > 300 km/h → return true (suspicious)
        // 6. Return false
    }
}
