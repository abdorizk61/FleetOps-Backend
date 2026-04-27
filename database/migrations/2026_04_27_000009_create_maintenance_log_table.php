<?php

/**
 * Migration: create_maintenance_log_table
 * DDL Source: FleetOpsDB.dbo.[MaintenanceLog] — SKELETON (DDL has empty table definition)
 * Execution Tier: 2
 *
 * TODO: The DDL provided has zero columns for this table.
 *       Add your real columns here. The `maintenance_parts_used` table's
 *       `log_id` FK will reference `log_id` (the PK added below).
 *
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_log', function (Blueprint $table) {
            // Skeleton PK — required for MaintenancePartsUsed FK reference
            $table->bigIncrements('log_id');

            // TODO: Add real columns here (e.g., vehicle_id, mechanic_id, description, etc.)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_log');
    }
};
