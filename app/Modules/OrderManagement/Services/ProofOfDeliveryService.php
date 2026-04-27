<?php

/**
 * @file: ProofOfDeliveryService.php
 * @description: خدمة إثبات التسليم (POD) - صورة وتوقيع (fn13/14 / OM-05)
 * @module: OrderManagement
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Services;

use App\Modules\OrderManagement\Repositories\OrderRepository;
use Illuminate\Http\UploadedFile;
use Exception;

class ProofOfDeliveryService
{
    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * حفظ إثبات التسليم (توقيع + صورة) (fn13/14)
     * @param int $orderId
     * @param array $data  (driver_id, lat, lng, customer_name, customer_signed, is_safe_drop)
     * @param UploadedFile|null $signature
     * @param UploadedFile|null $photo
     * @return mixed  ProofOfDelivery record
     * @throws Exception
     */
    public function storePOD(int $orderId, array $data, ?UploadedFile $signature, ?UploadedFile $photo)
    {
        // TODO: Store Proof of Delivery
        // 1. Validate order exists and is in_transit
        // 2. Upload signature to Azure Blob:
        //    $signatureUrl = Storage::disk('azure')->put("signatures/{$orderId}", $signature)
        // 3. Upload photo to Azure Blob:
        //    $photoUrl = Storage::disk('azure')->put("photos/{$orderId}", $photo)
        // 4. Create POD record with signature_url, photo_url, GPS, timestamp
        // 5. Update order status to 'delivered'
        // 6. Fire event: OrderDelivered → triggers notification + performance update
        // 7. Return POD record
    }
}
