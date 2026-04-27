<?php

/**
 * @file: AuditLog.php
 * @description: نموذج Eloquent لسجل المراجعة المعتمد - Logging & Audit Service (LA-01 / fn37)
 * @module: LoggingAudit
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\LoggingAudit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $primaryKey = 'audit_id';
    public $incrementing = true;

    // ⚠️ IMPORTANT: Audit logs are IMMUTABLE - no SoftDeletes, no updates after creation
    protected $fillable = [
        'user_id',
        'user_role',
        'action',           // created | updated | deleted | login | logout | status_changed
        'entity_type',      // order | vehicle | user | work_order | etc.
        'entity_id',
        'before_state',     // JSON snapshot before change (PII masked)
        'after_state',      // JSON snapshot after change (PII masked)
        'ip_address',
        'user_agent',
        'correlation_id',   // Trace ID linking related logs
        'module',           // Source module
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state'  => 'array',
        'created_at'   => 'datetime',
    ];

    // Audit logs are never updated — only created_at is written, no updated_at
    public $timestamps = false;

    const CREATED_AT = 'created_at';

    public static function boot()
    {
        parent::boot();

        // Prevent updates to audit logs (immutability enforcement)
        static::updating(function () {
            throw new \Exception('Audit logs are immutable and cannot be updated.');
        });

        static::deleting(function () {
            throw new \Exception('Audit logs cannot be deleted.');
        });
    }

    public function scopeForEntity($query, string $entityType, int $entityId)
    {
        return $query->where('entity_type', $entityType)->where('entity_id', $entityId);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
