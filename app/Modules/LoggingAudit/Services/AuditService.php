<?php

/**
 * @file AuditService.php
 * @description خدمة المراجعة — تكتب في audit_logs مع إخفاء PII تلقائياً
 * @module LoggingAudit
 * @author Team Leader (Khalid)
 */

namespace App\Modules\LoggingAudit\Services;

use App\Modules\LoggingAudit\Repositories\AuditLogRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class AuditService
{
    protected AuditLogRepository $auditLogRepository;

    protected array $piiFields = [
        'password', 'password_confirmation', 'phone', 'phone_no',
        'email', 'remember_token', 'fcm_token', 'token', 'secret',
        'customer_phone', 'customer_email', 'signature_url',
    ];

    protected ?string $correlationId = null;

    public function __construct(AuditLogRepository $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }

    /**
     * كتابة إدخال مراجعة (LA-01)
     *
     * @param string      $action      created | updated | deleted | login | logout | status_changed
     * @param string      $entityType  order | vehicle | user | driver | etc.
     * @param int         $entityId
     * @param array|null  $beforeState snapshot before mutation
     * @param array|null  $afterState  snapshot after mutation
     * @param int|null    $userId      null = system action
     * @param string      $module      source module name
     */
    public function log(
        string $action,
        string $entityType,
        int    $entityId,
        ?array $beforeState = null,
        ?array $afterState  = null,
        ?int   $userId      = null,
        string $module      = 'system'
    ): void {
        try {
            $userId   = $userId ?? Auth::id();
            $userRole = Auth::check() ? Auth::user()->role : 'system';

            $this->auditLogRepository->writeLog([
                'user_id'        => $userId,
                'user_role'      => $userRole,
                'action'         => $action,
                'entity_type'    => $entityType,
                'entity_id'      => $entityId,
                'before_state'   => $this->maskPII($beforeState),
                'after_state'    => $this->maskPII($afterState),
                'ip_address'     => Request::ip(),
                'user_agent'     => Request::userAgent(),
                'correlation_id' => $this->getCorrelationId(),
                'module'         => $module,
            ]);
        } catch (\Throwable $e) {
            logger()->error('[AuditService] Failed: ' . $e->getMessage());
        }
    }

    /**
     * إخفاء بيانات PII من السجلات (GDPR compliant)
     */
    protected function maskPII(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }
        foreach ($this->piiFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '***MASKED***';
            }
        }
        return $data;
    }

    /**
     * جلب أو إنشاء Correlation ID للطلب الحالي
     */
    protected function getCorrelationId(): string
    {
        if ($this->correlationId === null) {
            $this->correlationId =
                Request::header('X-Correlation-ID') ??
                Request::header('X-Request-ID') ??
                (string) Str::uuid();
        }
        return $this->correlationId;
    }

    /**
     * جلب سجلات مراجعة كيان معين
     */
    public function getEntityAuditTrail(string $entityType, int $entityId, int $perPage = 20)
    {
        return $this->auditLogRepository->getForEntity($entityType, $entityId, $perPage);
    }

    /**
     * جلب سجلات مراجعة مستخدم معين
     */
    public function getUserAuditTrail(int $userId, int $perPage = 20)
    {
        return $this->auditLogRepository->getForUser($userId, $perPage);
    }

    /**
     * البحث في سجلات المراجعة بفلاترة
     */
    public function searchLogs(array $filters, int $perPage = 20)
    {
        return $this->auditLogRepository->searchLogs($filters, $perPage);
    }
}
