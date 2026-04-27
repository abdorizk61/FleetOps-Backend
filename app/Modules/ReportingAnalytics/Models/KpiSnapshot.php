<?php

/**
 * @file: KpiSnapshot.php
 * @description: نموذج Eloquent لمقاطع KPI - Reporting & Analytics Service (AN-01)
 * @module: ReportingAnalytics
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\ReportingAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiSnapshot extends Model
{
    use HasFactory;

    protected $table = 'kpi_snapshots';
    protected $primaryKey = 'snapshot_id';
    public $incrementing = true;

    protected $fillable = [
        'metric_name',
        'metric_value',
        'metric_unit',
        'period_type',      // daily | weekly | monthly
        'period_start',
        'period_end',
        'entity_type',      // fleet | vehicle | driver
        'entity_id',
        'breakdown',        // JSON additional data
    ];

    protected $casts = [
        'metric_value' => 'float',
        'breakdown'    => 'array',
        'period_start' => 'datetime',
        'period_end'   => 'datetime',
        'created_at'   => 'datetime',
    ];
}
