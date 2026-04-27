<?php

/**
 * @file Inventory.php
 * @description Eloquent Model for the inventory table — Maintenance Module
 * @module Maintenance
 * @table inventory
 * @author Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $table      = 'inventory';
    protected $primaryKey = 'part_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // DDL uses datetimeoffset — Eloquent's default timestamp names match
    public $timestamps = true;
    const CREATED_AT   = 'created_at';
    const UPDATED_AT   = 'updated_at';

    /** @var array<string> */
    protected $fillable = [
        'part_name',
        'oem_number',
        'service_type',
        'compatible_models',  // JSON field — cast to array
        'quantity',
        'cost',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'compatible_models' => 'array',
        'quantity'          => 'integer',
        'cost'              => 'float',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Usage records for this part across maintenance jobs */
    public function usageRecords()
    {
        return $this->hasMany(MaintenancePartsUsed::class, 'part_id', 'part_id');
    }
}
