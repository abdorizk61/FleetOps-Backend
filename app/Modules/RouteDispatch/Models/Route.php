<?php

/**
 * @file Route.php
 * @description Eloquent Model for the routes table — RouteDispatch Module
 * @module RouteDispatch
 * @table routes
 * @author Team Leader (Khalid)
 */

namespace App\Modules\RouteDispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\AuthIdentity\Models\Driver;
use App\Modules\AuthIdentity\Models\Dispatcher;

class Route extends Model
{
    use HasFactory;

    protected $table      = 'routes';
    protected $primaryKey = 'route_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    // DDL has CreatedAt only (no UpdatedAt)
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'route_name',
        'driver_id',
        'dispatcher_id',
        'vehicle_id',
        'scheduled_start_time',
        'actual_start_time',
        'scheduled_end_time',
        'status',
        'total_distance',
        'total_stops',
        'fuel_consumption_est',
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'scheduled_start_time'  => 'datetime',
        'actual_start_time'     => 'datetime',
        'scheduled_end_time'    => 'datetime',
        'total_distance'        => 'float',
        'fuel_consumption_est'  => 'float',
        'total_stops'           => 'integer',
        'created_at'            => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Driver assigned to this route */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /** Dispatcher who planned this route */
    public function dispatcher()
    {
        return $this->belongsTo(Dispatcher::class, 'dispatcher_id', 'dispatcher_id');
    }

    /** Vehicle assigned to this route */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    /** All stops on this route */
    public function stops()
    {
        return $this->hasMany(RouteStop::class, 'route_id', 'route_id')->orderBy('stop_no');
    }
}
