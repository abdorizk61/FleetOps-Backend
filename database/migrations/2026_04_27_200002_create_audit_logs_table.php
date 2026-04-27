<?php

/**
 * Migration: create_audit_logs_table
 * DDL: Immutable audit trail — who did what, before/after state, for compliance
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('audit_id');

            $table->unsignedBigInteger('user_id')->nullable()->index();

            // Role snapshot at time of action
            $table->string('user_role', 30)->nullable();

            // Action: created | updated | deleted | login | logout | status_changed
            $table->string('action', 30)->index();

            // Entity that was mutated: order | vehicle | user | driver | etc.
            $table->string('entity_type', 50)->index();
            $table->unsignedBigInteger('entity_id')->index();

            // JSON snapshots of data before and after the change (PII-masked)
            $table->longText('before_state')->nullable();
            $table->longText('after_state')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 300)->nullable();
            $table->string('correlation_id', 36)->nullable()->index();
            $table->string('module', 50)->nullable();

            // Write-once — no updated_at
            $table->dateTimeTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
