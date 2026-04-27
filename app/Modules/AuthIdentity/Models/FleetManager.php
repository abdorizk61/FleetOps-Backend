<?php

/**
 * @file FleetManager.php
 * @description Eloquent Model for the fleet_managers table — AuthIdentity Module
 * @module AuthIdentity
 * @table fleet_managers
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Maintenance\Models\MaintenanceAssignment;

class FleetManager extends Model
{
    use HasFactory;

    protected $table      = 'fleet_managers';
    protected $primaryKey = 'fleet_manager_id';
    protected $keyType    = 'int';
    public $incrementing  = false; // PK is also a FK — no auto-increment

    // No UpdatedAt in DDL
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'fleet_manager_id', // Set by application (mirrors user_id)
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The base user account for this fleet manager */
    public function user()
    {
        return $this->belongsTo(User::class, 'fleet_manager_id', 'user_id');
    }

    /** Maintenance assignments created by this fleet manager */
    public function maintenanceAssignments()
    {
        return $this->hasMany(MaintenanceAssignment::class, 'fleet_manager_id', 'fleet_manager_id');
    }
}
