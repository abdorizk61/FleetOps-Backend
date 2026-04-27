<?php

/**
 * @file: VehicleInspectionService.php
 * @description: خدمة الفحوصات السنوية/الدورية للمركبات - Maintenance Service (MT-07 / fn32)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Services;

use App\Modules\Maintenance\Repositories\VehicleInspectionRepository;
use Exception;

class VehicleInspectionService
{
    protected VehicleInspectionRepository $inspectionRepository;

    public function __construct(VehicleInspectionRepository $inspectionRepository)
    {
        $this->inspectionRepository = $inspectionRepository;
    }

    public function getAllInspections(int $perPage = 15)
    {
        // TODO: return $this->inspectionRepository->paginate($perPage);
    }

    public function getInspectionById(int $id)
    {
        // TODO: return $this->inspectionRepository->findByIdOrFail($id);
    }

    public function createInspection(array $data)
    {
        // TODO: Create vehicle inspection record
        // 1. Create inspection
        // 2. If result === 'fail' → create work order automatically
        // 3. Notify manager
        // 4. Return created inspection
    }

    public function getVehicleInspections(int $vehicleId, int $perPage = 15)
    {
        // TODO: return $this->inspectionRepository->getForVehiclePaginated($vehicleId, $perPage);
    }

    /**
     * جلب المركبات ذات الفحص السنوي المتأخر (fn32)
     */
    public function getOverdueInspections()
    {
        // TODO: return $this->inspectionRepository->getOverdueInspections();
    }

    /**
     * فحص اقتراب موعد الفحص السنوي (MT-07)
     * يتم استدعاؤه من Scheduler يومياً
     * @return array  vehicles with upcoming inspections (within 30 days)
     */
    public function getUpcomingInspections(): array
    {
        // TODO: Get vehicles whose next_inspection_date is within 30 days
        // 1. Query inspections where next_inspection_date BETWEEN now() AND now() + 30 days
        // 2. Group by vehicle_id (latest inspection per vehicle)
        // 3. Return list of vehicles with days_remaining
    }
}
