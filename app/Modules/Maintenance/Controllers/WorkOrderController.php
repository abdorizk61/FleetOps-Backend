<?php

/**
 * @file: WorkOrderController.php
 * @description: متحكم أوامر العمل - Maintenance Service (MT-02/03/04/05/06/07)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Maintenance\Services\WorkOrderService;
use App\Modules\Maintenance\Requests\WorkOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    protected WorkOrderService $workOrderService;

    public function __construct(WorkOrderService $workOrderService)
    {
        $this->workOrderService = $workOrderService;
    }

    /** GET /api/v1/maintenance/work-orders */
    public function index(): JsonResponse
    {
        // TODO: return paginated work orders (with filters: status, vehicle_id, mechanic_id)
    }

    /** GET /api/v1/maintenance/work-orders/{id} */
    public function show(int $id): JsonResponse
    {
        // TODO: return single work order with vehicle info
    }

    /** POST /api/v1/maintenance/work-orders */
    public function store(WorkOrderRequest $request): JsonResponse
    {
        // TODO: $order = $this->workOrderService->createWorkOrder($request->validated())
        // return 201 with work order
    }

    /** PUT /api/v1/maintenance/work-orders/{id} */
    public function update(int $id, WorkOrderRequest $request): JsonResponse
    {
        // TODO: Update work order details (only if open/assigned)
    }

    /** DELETE /api/v1/maintenance/work-orders/{id} */
    public function destroy(int $id): JsonResponse
    {
        // TODO: Cancel/delete work order (only if open)
    }

    /**
     * تعيين ميكانيكي (MT-03 / fn30)
     * POST /api/v1/maintenance/work-orders/{id}/assign
     */
    public function assignMechanic(int $id, Request $request): JsonResponse
    {
        // TODO: Validate mechanic_id in request
        // $order = $this->workOrderService->assignMechanic($id, $request->mechanic_id)
        // return success response
    }

    /**
     * تحديث حالة أمر العمل (MT-02)
     * PATCH /api/v1/maintenance/work-orders/{id}/status
     */
    public function updateStatus(int $id, Request $request): JsonResponse
    {
        // TODO: Validate status field
        // $request->validate(['status' => 'required|in:in_progress,resolved,closed', 'repair_cost' => 'required_if:status,resolved|numeric'])
        // $order = $this->workOrderService->updateStatus($id, $request->status, $request->all())
    }

    /**
     * تسجيل قطع الغيار المستخدمة (fn26/31)
     * POST /api/v1/maintenance/work-orders/{id}/parts
     */
    public function recordParts(int $id, Request $request): JsonResponse
    {
        // TODO: Validate parts array: [['part_id' => 1, 'quantity' => 2], ...]
        // $this->workOrderService->recordPartsUsed($id, $request->parts)
    }

    /**
     * الطلبات المفتوحة فقط
     * GET /api/v1/maintenance/work-orders/open
     */
    public function openOrders(): JsonResponse
    {
        // TODO: return all open work orders
    }

    /**
     * أوامر عمل مركبة معينة
     * GET /api/v1/maintenance/work-orders/vehicle/{vehicleId}
     */
    public function forVehicle(int $vehicleId): JsonResponse
    {
        // TODO: return work orders for specific vehicle
    }

    /**
     * أوامر عمل ميكانيكي معين
     * GET /api/v1/maintenance/work-orders/mechanic/{mechanicId}
     */
    public function forMechanic(int $mechanicId): JsonResponse
    {
        // TODO: return work orders assigned to specific mechanic
    }
}
