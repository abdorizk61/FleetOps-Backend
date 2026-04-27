<?php

/**
 * @file: InspectionRepository.php
 * @description: مستودع بيانات فحوصات ما قبل الرحلة - Order Management Service (fn12)
 * @module: OrderManagement
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\OrderManagement\Models\PreTripInspection;

class InspectionRepository extends BaseRepository
{
    public function __construct(PreTripInspection $model)
    {
        parent::__construct($model);
    }

    /**
     * جلب آخر فحص لمركبة معينة
     * @param int $vehicleId
     * @return PreTripInspection|null
     */
    public function getLatestForVehicle(int $vehicleId): ?PreTripInspection
    {
        // TODO: return $this->model->where('vehicle_id', $vehicleId)->latest('inspected_at')->first();
    }

    /**
     * جلب فحوصات مسار معين
     * @param int $routeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForRoute(int $routeId)
    {
        // TODO: return $this->model->where('route_id', $routeId)->orderBy('inspected_at', 'desc')->get();
    }

    /**
     * جلب فحوصات مركبة معينة (مع Pagination)
     * @param int $vehicleId
     * @param int $perPage
     */
    public function getForVehiclePaginated(int $vehicleId, int $perPage = 15)
    {
        // TODO: return $this->model->where('vehicle_id', $vehicleId)->latest('inspected_at')->paginate($perPage);
    }
}
