<?php

/**
 * @file Mechanic.php
 * @description Eloquent Model for the mechanics table — AuthIdentity Module
 * @module AuthIdentity
 * @table mechanics
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Maintenance\Models\MaintenanceAssignment;

class Mechanic extends Model
{
    use HasFactory;

    protected $table      = 'mechanics';
    protected $primaryKey = 'mechanic_id';
    protected $keyType    = 'int';
    public $incrementing  = false; // PK is also a FK — no auto-increment

    // No UpdatedAt in DDL
    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    /** @var array<string> */
    protected $fillable = [
        'mechanic_id',      // Set by application (mirrors user_id)
        'created_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The base user account for this mechanic */
    public function user()
    {
        return $this->belongsTo(User::class, 'mechanic_id', 'user_id');
    }

    /** Maintenance assignments assigned to this mechanic */
    public function maintenanceAssignments()
    {
        return $this->hasMany(MaintenanceAssignment::class, 'mechanic_id', 'mechanic_id');
    }
}
