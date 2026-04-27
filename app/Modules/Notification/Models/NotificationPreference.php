<?php

/**
 * @file: NotificationPreference.php
 * @description: نموذج Eloquent لتفضيلات الإشعارات - Notification Service (NF-02)
 * @module: Notification
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $table = 'notification_preferences';
    protected $primaryKey = 'preference_id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'push_enabled',
        'sms_enabled',
        'email_enabled',
        'quiet_hours_start',  // HH:MM format
        'quiet_hours_end',    // HH:MM format
        'preferred_language', // ar | en
        'fcm_token',          // Firebase Cloud Messaging device token
    ];

    protected $casts = [
        'push_enabled' => 'boolean',
        'sms_enabled'  => 'boolean',
        'email_enabled' => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    protected $hidden = ['fcm_token'];
}
