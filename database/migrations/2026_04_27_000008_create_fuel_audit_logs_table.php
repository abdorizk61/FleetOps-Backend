<?php

/**
 * Migration: create_fuel_audit_logs_table
 * DDL Source: FleetOpsDB.dbo.FuelAuditLog
 * Execution Tier: 1 — FK → vehicles
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_audit_logs', function (Blueprint $table) {
            // DDL: FuelLogID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('fuel_log_id');

            // DDL: VehicleID bigint NOT NULL FK → vehicles (no cascade defined in DDL)
            $table->unsignedBigInteger('vehicle_id');

            // DDL: LogTs datetimeoffset(3) DEFAULT sysdatetimeoffset() NOT NULL
            $table->timestampTz('log_ts', 3)->useCurrent();

            // DDL: FuelQuantity decimal(8,2) NOT NULL — CHECK > 0
            $table->decimal('fuel_quantity', 8, 2);

            // DDL: UnitPrice decimal(8,4) NOT NULL — CHECK > 0
            $table->decimal('unit_price', 8, 4);

            // DDL: TotalCost AS (CONVERT([decimal](12,4),[FuelQuantity]*[UnitPrice])) PERSISTED
            // TODO: Adjust the storedAs() expression to match your DB engine's syntax.
            //       MySQL equivalent shown; remove if using a DB without computed column support.
            $table->decimal('total_cost', 12, 4)
                  ->storedAs('CAST(fuel_quantity * unit_price AS DECIMAL(12,4))')
                  ->nullable();

            // DDL: OdometerReading decimal(12,2) NOT NULL — CHECK >= 0
            $table->decimal('odometer_reading', 12, 2);

            // FK_FAL_Vehicle → vehicles.vehicle_id (no cascade in DDL)
            $table->foreign('vehicle_id')
                  ->references('vehicle_id')
                  ->on('vehicles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_audit_logs');
    }
};
