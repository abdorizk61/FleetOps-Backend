<?php

/**
 * Migration: create_incident_reports_table
 * DDL Source: FleetOpsDB.dbo.IncidentReport
 * Execution Tier: 2 — FK → drivers, vehicles
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            // DDL: IncidentID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('incident_id');

            // DDL: DriverID bigint NOT NULL FK → drivers (no cascade in DDL)
            $table->unsignedBigInteger('driver_id');

            // DDL: VehicleID bigint NOT NULL FK → vehicles (no cascade in DDL)
            $table->unsignedBigInteger('vehicle_id');

            // DDL: Type nvarchar(30) — CK_IR_Type
            $table->enum('type', [
                'accident', 'breakdown', 'traffic_violation', 'theft', 'cargo_damage', 'other',
            ]);

            // DDL: Severity nvarchar(10) DEFAULT 'medium' — CK_IR_Severity
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');

            // DDL: Description nvarchar(MAX) NULL
            $table->longText('description')->nullable();

            // DDL: Latitude decimal(10,7) NULL
            $table->decimal('latitude', 10, 7)->nullable();

            // DDL: Longitude decimal(10,7) NULL
            $table->decimal('longitude', 10, 7)->nullable();

            // DDL: PhotoUrls nvarchar(MAX) NULL — JSON validated by CK_IR_Photos
            $table->json('photo_urls')->nullable();

            // DDL: IncidentTs datetimeoffset(3) DEFAULT sysdatetimeoffset() NOT NULL
            $table->timestampTz('incident_ts', 3)->useCurrent();

            // DDL: CreatedAt datetimeoffset(3) DEFAULT sysdatetimeoffset() NOT NULL (no UpdatedAt)
            $table->timestampTz('created_at', 3)->useCurrent();

            // FK_IR_Driver → drivers.driver_id (no cascade in DDL)
            $table->foreign('driver_id')->references('driver_id')->on('drivers');

            // FK_IR_Vehicle → vehicles.vehicle_id (no cascade in DDL)
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
