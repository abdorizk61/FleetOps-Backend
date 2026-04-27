<?php

/**
 * @file: VehicleInspectionRepository.php
 * @description: مستودع بيانات الفحوصات الدورية/السنوية للمركبات - Maintenance Service (MT-07)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\Maintenance\Models\VehicleInspection;
use Illuminate\Database\Eloquent\Collection;

class VehicleInspectionRepository extends BaseRepository
{
    public function __construct(VehicleInspection $model)
    {
        parent::__construct($model);
    }

    /**
     * جلب آخر فحص لمركبة معينة
     * @param int $vehicleId
     * @return VehicleInspection|null
     */
    public function getLatestForVehicle(int $vehicleId): ?VehicleInspection
    {
        // TODO: return $this->model->forVehicle($vehicleId)->latest('inspection_date')->first();
    }

    /**
     * جلب المركبات التي فحصها السنوي متأخر (MT-07 / fn32)
     * @return Collection
     */
    public function getOverdueInspections(): Collection
    {
        // TODO: Get vehicles with overdue annual inspections
        // The query joins with latest inspection per vehicle and checks next_inspection_date < now()
        // return $this->model
        //     ->annual()
        //     ->where('next_inspection_date', '<', now())
        //     ->with('vehicle')
        //     ->get();
    }

    /**
     * جلب فحوصات مركبة معينة
     * @param int $vehicleId
     * @param int $perPage
     */
    public function getForVehiclePaginated(int $vehicleId, int $perPage = 15)
    {
        // TODO: return $this->model->forVehicle($vehicleId)->latest('inspection_date')->paginate($perPage);
    }
}
