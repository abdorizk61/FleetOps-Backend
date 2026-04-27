<?php

/**
 * @file: TrackingLinkController.php
 * @description: متحكم روابط التتبع العامة للعملاء (RT-01 / fn33)
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RealtimeTracking\Services\TrackingLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackingLinkController extends Controller
{
    protected TrackingLinkService $trackingLinkService;

    public function __construct(TrackingLinkService $trackingLinkService)
    {
        $this->trackingLinkService = $trackingLinkService;
    }

    /**
     * إنشاء رابط تتبع لطلب (Dispatcher فقط)
     * POST /api/v1/tracking/links
     */
    public function generate(Request $request): JsonResponse
    {
        // TODO: Generate tracking link
        // 1. Validate: ['order_id' => 'required|integer|exists:orders,order_id', 'expires_hours' => 'nullable|integer']
        // 2. $data = $this->trackingLinkService->generateTrackingLink($request->order_id, $request->all())
        // 3. Return URL and expiry
    }

    /**
     * جلب بيانات التتبع العامة (Public - لا يحتاج auth)
     * GET /api/v1/tracking/public/{token}
     */
    public function publicTracking(string $token): JsonResponse
    {
        // TODO: Get public tracking data (no auth required - validate signed URL)
        // 1. $data = $this->trackingLinkService->getPublicTrackingData($token)
        // 2. Return public-safe tracking data
        // 3. Catch Exception → 404 or 410 (link expired)
    }

    /**
     * إلغاء رابط تتبع
     * DELETE /api/v1/tracking/links/{id}
     */
    public function revoke(int $id): JsonResponse
    {
        // TODO: Revoke tracking link
        // 1. Deactivate the link
        // 2. Return success response
    }
}
