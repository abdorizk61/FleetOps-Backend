<?php

/**
 * @file: GeofenceController.php
 * @description: متحكم المناطق الجغرافية - Real-time Tracking & GPS Service (RT-04)
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RealtimeTracking\Services\GeofenceService;
use App\Modules\RealtimeTracking\Requests\GeofenceRequest;
use Illuminate\Http\JsonResponse;

class GeofenceController extends Controller
{
    protected GeofenceService $geofenceService;

    public function __construct(GeofenceService $geofenceService)
    {
        $this->geofenceService = $geofenceService;
    }

    /** GET /api/v1/tracking/geofences */
    public function index(): JsonResponse
    {
        // TODO: return all geofences (paginated)
        // return response()->json(['success' => true, 'data' => $this->geofenceService->getAllGeofences()]);
    }

    /** GET /api/v1/tracking/geofences/{id} */
    public function show(int $id): JsonResponse
    {
        // TODO: return single geofence
    }

    /** POST /api/v1/tracking/geofences */
    public function store(GeofenceRequest $request): JsonResponse
    {
        // TODO: Create geofence, return 201
    }

    /** PUT /api/v1/tracking/geofences/{id} */
    public function update(int $id, GeofenceRequest $request): JsonResponse
    {
        // TODO: Update geofence
    }

    /** DELETE /api/v1/tracking/geofences/{id} */
    public function destroy(int $id): JsonResponse
    {
        // TODO: Delete geofence
    }
}
