<?php

/**
 * @file: WorkOrderRepository.php
 * @description: مستودع بيانات أوامر العمل - Maintenance Service
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\Maintenance\Models\WorkOrder;
use Illuminate\Database\Eloquent\Collection;

class WorkOrderRepository extends BaseRepository
{
    public function __construct(WorkOrder $model)
    {
        parent::__construct($model);
    }

    public function getForVehicle(int $vehicleId): Collection
    {
        // TODO: return $this->model->forVehicle($vehicleId)->orderBy('opened_at', 'desc')->get();
    }

    public function getForMechanic(int $mechanicId): Collection
    {
        // TODO: return $this->model->forMechanic($mechanicId)->orderBy('opened_at', 'desc')->get();
    }

    public function updateStatus(int $workOrderId, string $newStatus, array $timestamps = []): bool
    {
        // TODO: Update work order status with appropriate timestamps
        // 1. Map status to timestamp field:
        //    assigned → assigned_at, in_progress → started_at, resolved → resolved_at, closed → closed_at
        // 2. Merge timestamps into update data
        // 3. return $this->update($workOrderId, array_merge(['status' => $newStatus], $timestamps))
    }

    public function getOpenWorkOrders(): Collection
    {
        // TODO: return $this->model->open()->with('vehicle')->get();
    }
}
