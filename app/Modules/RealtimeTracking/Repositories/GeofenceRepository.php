<?php

/**
 * @file: GeofenceRepository.php
 * @description: مستودع بيانات المناطق الجغرافية - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\RealtimeTracking\Models\Geofence;
use Illuminate\Database\Eloquent\Collection;

class GeofenceRepository extends BaseRepository
{
    public function __construct(Geofence $model)
    {
        parent::__construct($model);
    }

    /**
     * جلب جميع المناطق النشطة
     * @return Collection
     */
    public function getActiveGeofences(): Collection
    {
        // TODO: return $this->model->active()->get();
    }

    /**
     * التحقق من دخول نقطة إلى منطقة جغرافية (RT-04)
     * @param float $lat
     * @param float $lng
     * @return Collection  - geofences containing this point
     */
    public function findGeofencesContainingPoint(float $lat, float $lng): Collection
    {
        // TODO: Find all active geofences that contain the given lat/lng
        // For circle type: use Haversine formula to check if distance < radius
        // For polygon type: use point-in-polygon algorithm
        // Return matching geofences
    }
}
