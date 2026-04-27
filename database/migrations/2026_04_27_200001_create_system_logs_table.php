<?php

/**
 * Migration: create_system_logs_table
 * DDL: Structured log entries for system/audit/security/performance events
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->bigIncrements('log_id');

            // Level: debug | info | warning | error | critical
            $table->string('level', 20)->index();

            // Channel: app | security | performance | audit
            $table->string('channel', 30)->index();

            $table->string('message', 500);

            // JSON: additional contextual data (payload, ip, user_agent, status_code)
            $table->longText('context')->nullable();

            // Trace ID for cross-service/request correlation
            $table->string('correlation_id', 36)->nullable()->index();

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('module', 50)->nullable();
            $table->string('request_uri', 500)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();

            // Manual timestamp — no updated_at (write-once)
            $table->dateTimeTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
