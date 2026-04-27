<?php

/**
 * @file: ProofOfDeliveryController.php
 * @description: متحكم إثبات التسليم (POD) - صورة وتوقيع (fn13/14)
 * @module: OrderManagement
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\OrderManagement\Services\ProofOfDeliveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProofOfDeliveryController extends Controller
{
    protected ProofOfDeliveryService $podService;

    public function __construct(ProofOfDeliveryService $podService)
    {
        $this->podService = $podService;
    }

    /**
     * حفظ إثبات التسليم
     * POST /api/v1/orders/{orderId}/pod
     */
    public function store(int $orderId, Request $request): JsonResponse
    {
        // TODO: Store Proof of Delivery
        // 1. Validate request:
        //    'driver_id' => required|integer
        //    'lat' => required|numeric
        //    'lng' => required|numeric
        //    'customer_name' => required|string
        //    'customer_signed' => required|boolean
        //    'is_safe_drop' => required|boolean
        //    'signature' => required_if:customer_signed,true|file|mimes:png,jpg|max:2048
        //    'photo' => nullable|file|mimes:png,jpg|max:5120
        // 2. $pod = $this->podService->storePOD($orderId, $request->validated(), $request->file('signature'), $request->file('photo'))
        // 3. return response()->json(['success' => true, 'message' => 'تم تسجيل إثبات التسليم', 'data' => $pod], 201)
    }

    /**
     * عرض إثبات التسليم لطلب معين
     * GET /api/v1/orders/{orderId}/pod
     */
    public function show(int $orderId): JsonResponse
    {
        // TODO: Get POD for order
        // return POD record with URLs
    }
}
