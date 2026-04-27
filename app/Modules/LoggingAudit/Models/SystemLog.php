<?php

/**
 * @file: SystemLog.php
 * @description: نموذج Eloquent للسجلات النظامية البنيوية - Logging & Audit Service (LA-02)
 * @module: LoggingAudit
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\LoggingAudit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'system_logs';
    protected $primaryKey = 'log_id';
    public $incrementing = true;

    protected $fillable = [
        'level',            // debug | info | warning | error | critical
        'channel',          // app | security | performance | audit
        'message',
        'context',          // JSON additional data
        'correlation_id',   // Trace ID for request tracing
        'user_id',
        'module',
        'request_uri',
        'duration_ms',      // Request duration in milliseconds
    ];

    protected $casts = [
        'context'    => 'array',
        'duration_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function scopeByLevel($query, string $level) { return $query->where('level', $level); }
    public function scopeByChannel($query, string $channel) { return $query->where('channel', $channel); }
    public function scopeErrors($query) { return $query->whereIn('level', ['error', 'critical']); }
}
