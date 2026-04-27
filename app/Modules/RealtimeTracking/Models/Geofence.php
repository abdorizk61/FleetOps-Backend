<?php

/**
 * @file: Geofence.php
 * @description: نموذج Eloquent للمناطق الجغرافية - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Geofence extends Model
{
    use HasFactory;

    protected $table = 'geofences';
    protected $primaryKey = 'geofence_id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'type',         // circle | polygon | rectangle
        'center_lat',
        'center_lng',
        'radius_m',     // For circle type
        'coordinates',  // JSON polygon points
        'is_active',
        'trigger_on',   // entry | exit | both
        'description',
    ];

    protected $casts = [
        'center_lat'  => 'float',
        'center_lng'  => 'float',
        'radius_m'    => 'float',
        'coordinates' => 'array',
        'is_active'   => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
