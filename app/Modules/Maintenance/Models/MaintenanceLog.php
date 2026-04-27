<?php

/**
 * @file MaintenanceLog.php
 * @description Eloquent Model for the maintenance_log table — Maintenance Module
 * @module Maintenance
 * @table maintenance_log
 *
 * NOTE: This is a SKELETON model. The DDL provided has zero columns for this table.
 *       TODO: Add real properties once the table schema is finalized.
 *
 * @author Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table      = 'maintenance_log';
    protected $primaryKey = 'log_id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    public $timestamps = true;

    /**
     * @var array<string>
     * TODO: Populate once the real columns are added to the migration.
     */
    protected $fillable = [
        // 'vehicle_id', 'mechanic_id', 'description', ...
    ];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Parts used in this maintenance log entry */
    public function partsUsed()
    {
        return $this->hasMany(MaintenancePartsUsed::class, 'log_id', 'log_id');
    }
}
