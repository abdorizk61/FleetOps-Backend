<?php

namespace App\Modules\Maintenance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Maintenance\Services\EmergencyDispatchService;

class EmergencyDispatchController extends Controller
{
    protected EmergencyDispatchService $dispatchService;

    public function __construct(EmergencyDispatchService $dispatchService)
    {
        $this->dispatchService = $dispatchService;
    }

    /**
     * GET emergency-incidents
     * List of active vehicle breakdowns and incidents.
     */
    public function incidents(): JsonResponse
    {
        try {
            $data = $this->dispatchService->getActiveIncidents();

            return response()->json([
                'success' => true,
                'message' => "تم جلب البيانات بنجاح",
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "فشل جلب البيانات",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET emergency-incident-details
     * Detailed data for a specific incident.
     */
    public function incidentDetails(int $id): JsonResponse
    {
        try {
            $data = $this->dispatchService->getIncidentDetails($id);

            return response()->json([
                'success' => true,
                'message' => "تم جلب البيانات بنجاح",
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "فشل جلب البيانات",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET emergency-nearby-mechanics
     * List of mechanics sorted by distance to the incident.
     */
    public function nearbyMechanics(int $id): JsonResponse
    {
        try {
            $data = $this->dispatchService->getNearbyMechanics($id);

            return response()->json([
                'success' => true,
                'message' => "تم جلب البيانات بنجاح",
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "فشل جلب البيانات",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST emergency-dispatch-mechanic
     * Dispatches a mechanic and creates an emergency work order.
     */
    public function dispatchMechanic(int $id, Request $request): JsonResponse
    {
        try {
            $data = $this->dispatchService->dispatchMechanic($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => "تم التعيين بنجاح",
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "فشل التعيين",
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
