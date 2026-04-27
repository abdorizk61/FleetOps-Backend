<?php

/**
 * @file: Notification.php
 * @description: نموذج Eloquent للإشعارات - Notification Service
 * @module: Notification
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'channel',          // push | sms | email
        'event_type',       // proximity_alert | delay_alert | status_update | maintenance_alert
        'payload',          // JSON - notification content
        'status',           // pending | sent | delivered | failed
        'dedup_key',        // For deduplication guard
        'retry_count',
        'sent_at',
        'delivered_at',
        'failed_reason',
    ];

    protected $casts = [
        'payload'      => 'array',
        'retry_count'  => 'integer',
        'sent_at'      => 'datetime',
        'delivered_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    protected $attributes = [
        'status'      => 'pending',
        'retry_count' => 0,
    ];

    public function scopePending($query)  { return $query->where('status', 'pending'); }
    public function scopeFailed($query)   { return $query->where('status', 'failed'); }
    public function scopeForUser($query, int $userId) { return $query->where('user_id', $userId); }
}
