<?php

/**
 * @file: DriverPerformanceScore.php
 * @description: نموذج Eloquent لنقاط أداء السائقين - Reporting & Analytics Service (AN-02 / fn22)
 * @module: ReportingAnalytics
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\ReportingAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverPerformanceScore extends Model
{
    use HasFactory;

    protected $table = 'driver_performance_scores';
    protected $primaryKey = 'score_id';
    public $incrementing = true;

    protected $fillable = [
        'driver_id',
        'period_start',
        'period_end',
        'on_time_rate',         // % of deliveries on time (weight A)
        'fuel_efficiency_score', // normalized score (weight B)
        'customer_rating_avg',  // avg from feedback (weight C)
        'composite_score',      // final weighted score 0-100
        'total_deliveries',
        'successful_deliveries',
        'breakdown',            // JSON with detailed metrics
    ];

    protected $casts = [
        'on_time_rate'           => 'float',
        'fuel_efficiency_score'  => 'float',
        'customer_rating_avg'    => 'float',
        'composite_score'        => 'float',
        'total_deliveries'       => 'integer',
        'successful_deliveries'  => 'integer',
        'breakdown'              => 'array',
        'period_start'           => 'datetime',
        'period_end'             => 'datetime',
        'created_at'             => 'datetime',
    ];
}