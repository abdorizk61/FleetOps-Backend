<?php

/**
 * @file: LocationController.php
 * @description: متحكم GPS وتحديثات الموقع الحي - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RealtimeTracking\Services\LocationService;
use App\Modules\RealtimeTracking\Requests\LocationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * استقبال تحديث موقع السائق (RT-02)
     * POST /api/v1/tracking/location
     */
    public function ingest(LocationRequest $request): JsonResponse
    {
        // TODO: Process GPS ping
        // 1. $result = $this->locationService->ingestLocation($request->validated())
        // 2. Return: response()->json(['success' => true, 'message' => 'تم تسجيل الموقع', 'data' => $result], 201)
        // 3. Catch Exception
    }

    /**
     * جلب آخر موقع معروف لسائق (RT-07)
     * GET /api/v1/tracking/drivers/{driverId}/last-location
     */
    public function lastKnown(int $driverId): JsonResponse
    {
        // TODO: Get last known location
        // 1. $location = $this->locationService->getLastKnownLocation($driverId)
        // 2. Return location or 'لا يوجد موقع محفوظ'
    }

    /**
     * جلب مسار رحلة معينة (fn38 - Historical Playback)
     * GET /api/v1/tracking/routes/{routeId}/trail
     */
    public function routeTrail(int $routeId): JsonResponse
    {
        // TODO: Return full GPS trail for route
        // 1. $trail = $this->locationService->getRouteTrail($routeId)
        // 2. Return trail data
    }

    /**
     * التحقق من حالة السائق (Online/Offline) (RT-05)
     * GET /api/v1/tracking/drivers/{driverId}/status
     */
    public function driverStatus(int $driverId): JsonResponse
    {
        // TODO: Check driver heartbeat status
        // 1. $isOffline = $this->locationService->isDriverOffline($driverId)
        // 2. Return status data
    }
}
