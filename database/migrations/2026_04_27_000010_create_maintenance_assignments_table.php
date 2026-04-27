<?php

/**
 * Migration: create_maintenance_assignments_table
 * DDL Source: FleetOpsDB.dbo.MaintenanceAssignment
 * Execution Tier: 2 — FK → vehicles, fleet_managers, mechanics
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_assignments', function (Blueprint $table) {
            // DDL: AssignmentID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('assignment_id');

            // DDL: VehicleID bigint NOT NULL FK → vehicles
            $table->unsignedBigInteger('vehicle_id');

            // DDL: FleetManagerID bigint NOT NULL FK → fleet_managers
            $table->unsignedBigInteger('fleet_manager_id');

            // DDL: MechanicID bigint NULL FK → mechanics
            $table->unsignedBigInteger('mechanic_id')->nullable();

            // DDL: ServiceType nvarchar(30) — CK_MA_ServiceType
            $table->enum('service_type', [
                'oil_change', 'tire_rotation', 'brake_service', 'engine_repair',
                'transmission', 'electrical', 'bodywork', 'inspection', 'other',
            ]);

            // DDL: Priority nvarchar(10) DEFAULT 'medium' — CK_MA_Priority
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            // DDL: Status nvarchar(30) DEFAULT 'open'
            $table->string('status', 30)->default('open');

            // DDL: Issue nvarchar(MAX) NULL
            $table->longText('issue')->nullable();

            // DDL: CreatedAt datetimeoffset(3) DEFAULT sysdatetimeoffset()
            $table->timestampTz('created_at', 3)->useCurrent();

            // DDL: UpdatedAt datetimeoffset(3) DEFAULT sysdatetimeoffset()
            $table->timestampTz('updated_at', 3)->useCurrent();

            // FK_MA_Vehicle → vehicles (no cascade in DDL)
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');

            // FK_MA_FM → fleet_managers (no cascade in DDL)
            $table->foreign('fleet_manager_id')->references('fleet_manager_id')->on('fleet_managers');

            // FK_MA_Mechanic → mechanics (no cascade in DDL)
            $table->foreign('mechanic_id')->references('mechanic_id')->on('mechanics')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_assignments');
    }
};
