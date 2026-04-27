<?php

/**
 * @file: SparePart.php
 * @description: نموذج Eloquent لقطع الغيار - Maintenance Service (fn31 / MT-05)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SparePart extends Model
{
    use HasFactory;

    protected $table = 'spare_parts';
    protected $primaryKey = 'part_id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'sku',
        'category',         // oil | filter | tire | brake | battery | bulb | other
        'unit_price',
        'stock_quantity',
        'minimum_stock',    // Reorder alert threshold
        'reorder_level',
        'supplier_name',
        'supplier_lead_days',
        'unit',             // pcs | liter | kg
        'description',
    ];

    protected $casts = [
        'unit_price'         => 'float',
        'stock_quantity'     => 'integer',
        'minimum_stock'      => 'integer',
        'reorder_level'      => 'integer',
        'supplier_lead_days' => 'integer',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }
}
