<?php

/**
 * @file: VehicleInspection.php
 * @description: نموذج Eloquent لفحص دوري/سنوي للمركبة - Maintenance Service (MT-07 / fn32)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleInspection extends Model
{
    use HasFactory;

    protected $table = 'vehicle_inspections';
    protected $primaryKey = 'inspection_id';
    public $incrementing = true;

    protected $fillable = [
        'vehicle_id',
        'inspector_id',         // staff member who did the inspection
        'inspection_type',      // annual | periodic | on_demand
        'result',               // pass | fail | conditional_pass
        'inspection_date',
        'next_inspection_date',
        'notes',
        'certificate_number',
        'certificate_url',      // Azure Blob URL for scanned certificate
        'failure_points',       // JSON array of failed inspection points
        'cost',
    ];

    protected $casts = [
        'failure_points'        => 'array',
        'cost'                  => 'float',
        'inspection_date'       => 'datetime',
        'next_inspection_date'  => 'datetime',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    public function scopeForVehicle($query, int $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeAnnual($query)
    {
        return $query->where('inspection_type', 'annual');
    }

    /**
     * هل الفحص القادم متأخر؟
     */
    public function isOverdue(): bool
    {
        return $this->next_inspection_date && $this->next_inspection_date->isPast();
    }
}
