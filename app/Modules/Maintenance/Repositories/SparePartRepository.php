<?php

/**
 * @file: SparePartRepository.php
 * @description: مستودع بيانات قطع الغيار - Maintenance Service (fn31)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Repositories;

use App\Modules\Shared\Repositories\BaseRepository;
use App\Modules\Maintenance\Models\SparePart;
use Illuminate\Database\Eloquent\Collection;

class SparePartRepository extends BaseRepository
{
    public function __construct(SparePart $model)
    {
        parent::__construct($model);
    }

    public function getLowStockParts(): Collection
    {
        // TODO: return $this->model->lowStock()->get();
    }

    public function deductStock(int $partId, int $quantity): bool
    {
        // TODO: Deduct quantity from stock
        // 1. Get current stock
        // 2. Validate: if stock < quantity → throw Exception('المخزون غير كافي')
        // 3. $this->model->where('part_id', $partId)->decrement('stock_quantity', $quantity)
        // 4. Check if new stock <= minimum → trigger reorder alert
        // 5. Return true
    }

    public function addStock(int $partId, int $quantity): bool
    {
        // TODO: $this->model->where('part_id', $partId)->increment('stock_quantity', $quantity);
        // return true;
    }
}
