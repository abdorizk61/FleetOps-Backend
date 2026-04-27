<?php

/**
 * @file: SparePartController.php
 * @description: متحكم قطع الغيار والمخزون - Maintenance Service (fn31 / MT-05)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Maintenance\Services\SparePartService;
use App\Modules\Maintenance\Requests\SparePartRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    protected SparePartService $sparePartService;

    public function __construct(SparePartService $sparePartService)
    {
        $this->sparePartService = $sparePartService;
    }

    /** GET /api/v1/maintenance/parts */
    public function index(): JsonResponse
    {
        // TODO: return paginated parts list (with filter by category)
    }

    /** GET /api/v1/maintenance/parts/{id} */
    public function show(int $id): JsonResponse
    {
        // TODO: return single part
    }

    /** POST /api/v1/maintenance/parts */
    public function store(SparePartRequest $request): JsonResponse
    {
        // TODO: Create part → 201
    }

    /** PUT /api/v1/maintenance/parts/{id} */
    public function update(int $id, SparePartRequest $request): JsonResponse
    {
        // TODO: Update part
    }

    /** DELETE /api/v1/maintenance/parts/{id} */
    public function destroy(int $id): JsonResponse
    {
        // TODO: Delete part
    }

    /**
     * قطع الغيار المنخفضة في المخزون
     * GET /api/v1/maintenance/parts/low-stock
     */
    public function lowStock(): JsonResponse
    {
        // TODO: return $this->sparePartService->getLowStockParts()
    }

    /**
     * تعديل المخزون يدوياً (إضافة/خصم)
     * POST /api/v1/maintenance/parts/{id}/adjust-stock
     */
    public function adjustStock(int $id, Request $request): JsonResponse
    {
        // TODO: Validate: quantity (integer, min:1), operation (in:add,deduct)
        // $this->sparePartService->adjustStock($id, $request->quantity, $request->operation)
    }
}
