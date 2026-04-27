<?php

/**
 * Migration: create_maintenance_parts_used_table
 * DDL Source: FleetOpsDB.dbo.MaintenancePartsUsed
 * Execution Tier: 2 — FK → inventory; log_id references maintenance_log (no FK in DDL)
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_parts_used', function (Blueprint $table) {
            // DDL: UsageId bigint IDENTITY(1,1) — PK
            $table->bigIncrements('usage_id');

            // DDL: LogId bigint NOT NULL — references maintenance_log.log_id
            // NOTE: No explicit FK constraint was defined in the DDL (table was empty).
            //       TODO: Uncomment the foreign key below once MaintenanceLog columns are finalized.
            $table->unsignedBigInteger('log_id');
            // $table->foreign('log_id')->references('log_id')->on('maintenance_log');

            // DDL: PartId int NOT NULL FK → inventory.part_id (FK_MPU_Part)
            $table->unsignedInteger('part_id');

            // DDL: QuantityUsed int NOT NULL — CHECK > 0
            $table->unsignedInteger('quantity_used');

            // DDL: UnitCost decimal(10,2) NULL
            $table->decimal('unit_cost', 10, 2)->nullable();

            // DDL: UNIQUE (LogId, PartId)
            $table->unique(['log_id', 'part_id'], 'uq_mpu_log_part');

            // FK_MPU_Part → inventory.part_id (no cascade in DDL)
            $table->foreign('part_id')->references('part_id')->on('inventory');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_parts_used');
    }
};
