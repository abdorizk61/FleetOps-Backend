<?php

/**
 * @file: TrackingLink.php
 * @description: نموذج Eloquent لروابط التتبع العامة - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackingLink extends Model
{
    use HasFactory;

    protected $table = 'tracking_links';
    protected $primaryKey = 'link_id';
    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'token',
        'expires_at',
        'is_active',
        'access_count',
        'customer_phone',
        'customer_email',
    ];

    protected $hidden = ['token'];

    protected $casts = [
        'expires_at'   => 'datetime',
        'is_active'    => 'boolean',
        'access_count' => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('expires_at', '>', now());
    }
}
