<?php

/**
 * @file FuelAuditLog.php
 * @description Eloquent Model for the fuel_audit_logs table — ReportingAnalytics Module
 * @module ReportingAnalytics
 * @table fuel_audit_logs
 * @author Team Leader (Khalid)
 */

namespace App\Modules\ReportingAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\RouteDispatch\Models\Vehicle;

class FuelAuditLog extends Model
{
    use HasFactory;

    protected $table      = 'fuel_audit_logs';
    protected $primaryKey = 'fuel_log_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // No standard timestamps in DDL — has log_ts
    public $timestamps = false;

    /** @var array<string> */
    protected $fillable = [
        'vehicle_id',
        'log_ts',
        'fuel_quantity',
        'unit_price',
        // total_cost is a stored/computed column — do not include in fillable
        'odometer_reading',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'log_ts'           => 'datetime',
        'fuel_quantity'    => 'float',
        'unit_price'       => 'float',
        'total_cost'       => 'float',  // computed/stored column — read-only
        'odometer_reading' => 'float',
    ];

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /** Calculate total cost in PHP (fallback if DB computed column unavailable) */
    public function calculateTotalCost(): float
    {
        return round($this->fuel_quantity * $this->unit_price, 4);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The vehicle that was refueled */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
