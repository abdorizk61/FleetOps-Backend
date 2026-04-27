<?php

/**
 * @file PreTripInspection.php
 * @description Eloquent Model for the pre_trip_inspections table — OrderManagement Module
 * @module OrderManagement
 * @table pre_trip_inspections
 * @author Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\AuthIdentity\Models\Driver;
use App\Modules\RouteDispatch\Models\Vehicle;

class PreTripInspection extends Model
{
    use HasFactory;

    protected $table      = 'pre_trip_inspections';
    protected $primaryKey = 'inspection_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // No timestamps in DDL (has inspection_ts instead)
    public $timestamps = false;

    /** @var array<string> */
    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'inspection_ts',
        'odometer_reading',
        'fuel_level',
        'tires_ok',
        'brakes_ok',
        'lights_ok',
        'fluids_ok',
        // is_success is a computed/stored column — do not include in fillable
    ];

    /** @var array<string, string> */
    protected $casts = [
        'inspection_ts'    => 'datetime',
        'odometer_reading' => 'float',
        'fuel_level'       => 'integer',
        'tires_ok'         => 'boolean',
        'brakes_ok'        => 'boolean',
        'lights_ok'        => 'boolean',
        'fluids_ok'        => 'boolean',
        'is_success'       => 'boolean',
    ];

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /** Returns true if all checklist items passed (mirrors computed column) */
    public function allChecksPassed(): bool
    {
        return $this->tires_ok && $this->brakes_ok && $this->lights_ok && $this->fluids_ok;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The driver who performed this inspection */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /** The vehicle that was inspected */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
