<?php

/**
 * @file: RouteStopRepository.php
 * @description: مستودع بيانات محطات المسار - Route & Dispatch Service
 * @module: RouteDispatch
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RouteDispatch\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\RouteDispatch\Models\RouteStop;
use Illuminate\Database\Eloquent\Collection;

class RouteStopRepository extends BaseRepository
{
    public function __construct(RouteStop $model)
    {
        parent::__construct($model);
    }

    /**
     * جلب محطات مسار مرتبة بالتسلسل
     * @param int $routeId
     * @return Collection
     */
    public function getForRoute(int $routeId): Collection
    {
        // TODO: return $this->model->where('route_id', $routeId)->orderBy('sequence')->get();
    }

    /**
     * تحديث ترتيب المحطات بعد التحسين (TSP)
     * @param array $stopsData  [['stop_id' => int, 'sequence' => int], ...]
     * @return bool
     */
    public function reorderStops(array $stopsData): bool
    {
        // TODO: Bulk update sequences
        // foreach ($stopsData as $stop) {
        //     $this->model->where('stop_id', $stop['stop_id'])->update(['sequence' => $stop['sequence']]);
        // }
        // return true;
    }

    /**
     * تحديث ETA لمحطة
     * @param int $stopId
     * @param \DateTime $eta
     * @return bool
     */
    public function updateEta(int $stopId, \DateTime $eta): bool
    {
        // TODO: return $this->update($stopId, ['eta' => $eta]);
    }

    /**
     * تحديث حالة المحطة عند الوصول
     * @param int $stopId
     * @param string $status  (arrived | completed | skipped)
     * @return bool
     */
    public function updateStatus(int $stopId, string $status): bool
    {
        // TODO: Update stop status with timestamps
        // $data = ['status' => $status];
        // if ($status === 'arrived') $data['actual_arrival'] = now();
        // if ($status === 'completed') $data['departure_at'] = now();
        // return $this->update($stopId, $data);
    }

    /**
     * إضافة محطة في موضع معين (Express Insertion - fn07)
     * @param array $stopData
     * @param int   $afterSequence  رقم التسلسل قبل موضع الإدراج
     * @return RouteStop
     */
    public function insertAtPosition(array $stopData, int $afterSequence): RouteStop
    {
        // TODO: Insert stop at position
        // 1. Shift all stops with sequence > $afterSequence up by 1
        //    $this->model->where('route_id', $stopData['route_id'])
        //                ->where('sequence', '>', $afterSequence)
        //                ->increment('sequence');
        // 2. Set new stop sequence = $afterSequence + 1
        // 3. Create and return new stop
    }
}
