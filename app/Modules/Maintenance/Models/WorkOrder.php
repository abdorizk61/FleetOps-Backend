<?php

/**
 * @file: WorkOrder.php
 * @description: نموذج Eloquent لأوامر العمل - Maintenance Service
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 * 
 * Work Order States: open → assigned → in_progress → resolved → closed
 */

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'work_orders';
    protected $primaryKey = 'work_order_id';
    public $incrementing = true;

    protected $fillable = [
        'vehicle_id',
        'mechanic_id',
        'created_by',
        'type',         // routine | emergency | breakdown
        'status',       // open | assigned | in_progress | resolved | closed
        'description',
        'repair_cost',
        'parts_used',   // JSON array of parts used
        'priority',     // low | medium | high | critical
        'odometer_at_service',
        'opened_at',
        'assigned_at',
        'started_at',
        'resolved_at',
        'closed_at',
        'notes',
    ];

    protected $casts = [
        'repair_cost'       => 'float',
        'parts_used'        => 'array',
        'odometer_at_service' => 'float',
        'opened_at'         => 'datetime',
        'assigned_at'       => 'datetime',
        'started_at'        => 'datetime',
        'resolved_at'       => 'datetime',
        'closed_at'         => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    protected $attributes = [
        'status'   => 'open',
        'priority' => 'medium',
    ];

    public function scopeOpen($query)       { return $query->where('status', 'open'); }
    public function scopeAssigned($query)   { return $query->where('status', 'assigned'); }
    public function scopeInProgress($query) { return $query->where('status', 'in_progress'); }
    public function scopeForVehicle($query, int $vehicleId) { return $query->where('vehicle_id', $vehicleId); }
    public function scopeForMechanic($query, int $mechanicId) { return $query->where('mechanic_id', $mechanicId); }

    public function vehicle()
    {
        return $this->belongsTo(\App\Modules\RouteDispatch\Models\Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
