<?php

/**
 * @file LogService.php
 * @description خدمة السجلات النظامية البنيوية — تكتب في system_logs عبر SystemLogRepository
 * @module LoggingAudit
 * @author Team Leader (Khalid)
 */

namespace App\Modules\LoggingAudit\Services;

use App\Modules\LoggingAudit\Repositories\SystemLogRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class LogService
{
    protected SystemLogRepository $systemLogRepository;

    /** Reusable correlation ID for the lifetime of the current request */
    protected ?string $correlationId = null;

    public function __construct(SystemLogRepository $systemLogRepository)
    {
        $this->systemLogRepository = $systemLogRepository;
    }

    /**
     * كتابة سجل نظامي عام
     *
     * @param string $level   debug | info | warning | error | critical
     * @param string $channel app | security | performance | audit
     */
    public function log(
        string $level,
        string $message,
        array  $context = [],
        string $channel = 'app',
        string $module  = 'system',
        ?int   $durationMs = null
    ): void {
        try {
            $this->systemLogRepository->writeLog([
                'level'          => $level,
                'channel'        => $channel,
                'message'        => $message,
                'context'        => $context,
                'correlation_id' => $this->getCorrelationId(),
                'user_id'        => Auth::id(),
                'module'         => $module,
                'request_uri'    => Request::getRequestUri(),
                'duration_ms'    => $durationMs,
            ]);
        } catch (\Throwable $e) {
            // Never let logging break the application
            logger()->error('[LogService] Failed: ' . $e->getMessage());
        }
    }

    /**
     * سجل طلب HTTP مع مدته — يُنبّه إذا تجاوز 2000ms
     */
    public function logPerformance(string $uri, int $durationMs, array $context = []): void
    {
        $level = $durationMs > 2000 ? 'warning' : 'info';
        $this->log(
            $level,
            "Request {$uri} took {$durationMs}ms",
            array_merge($context, ['duration_ms' => $durationMs]),
            'performance',
            'system',
            $durationMs
        );
    }

    /**
     * سجل حدث أمني (failed_login | unauthorized_access | token_revoked | suspicious_ip)
     */
    public function logSecurity(string $event, array $context = [], string $module = 'AuthIdentity'): void
    {
        $this->log('warning', "Security Event: {$event}", $context, 'security', $module);
    }

    // ─── Convenience Helpers ──────────────────────────────────────────────────

    public function info(string $message, array $context = [], string $module = 'system'): void
    {
        $this->log('info', $message, $context, 'app', $module);
    }

    public function warning(string $message, array $context = [], string $module = 'system'): void
    {
        $this->log('warning', $message, $context, 'app', $module);
    }

    public function error(string $message, array $context = [], string $module = 'system'): void
    {
        $this->log('error', $message, $context, 'app', $module);
    }

    public function critical(string $message, array $context = [], string $module = 'system'): void
    {
        $this->log('critical', $message, $context, 'app', $module);
    }

    /**
     * جلب أو إنشاء Correlation ID ثابت لعمر الطلب الحالي
     */
    public function getCorrelationId(): string
    {
        if ($this->correlationId === null) {
            $this->correlationId =
                Request::header('X-Correlation-ID') ??
                Request::header('X-Request-ID') ??
                (string) Str::uuid();
        }
        return $this->correlationId;
    }
}
