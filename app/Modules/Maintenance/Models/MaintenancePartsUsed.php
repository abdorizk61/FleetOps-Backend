<?php

/**
 * @file MaintenancePartsUsed.php
 * @description Eloquent Model for the maintenance_parts_used table — Maintenance Module
 * @module Maintenance
 * @table maintenance_parts_used
 * @author Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenancePartsUsed extends Model
{
    use HasFactory;

    protected $table      = 'maintenance_parts_used';
    protected $primaryKey = 'usage_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // No timestamps in DDL
    public $timestamps = false;

    /** @var array<string> */
    protected $fillable = [
        'log_id',
        'part_id',
        'quantity_used',
        'unit_cost',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'quantity_used' => 'integer',
        'unit_cost'     => 'float',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The maintenance log entry this usage belongs to */
    public function maintenanceLog()
    {
        return $this->belongsTo(MaintenanceLog::class, 'log_id', 'log_id');
    }

    /** The inventory part that was used */
    public function part()
    {
        return $this->belongsTo(Inventory::class, 'part_id', 'part_id');
    }
}
