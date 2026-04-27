<?php

/**
 * Migration: create_pre_trip_inspections_table
 * DDL Source: FleetOpsDB.dbo.PreTripInspection
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
        Schema::create('pre_trip_inspections', function (Blueprint $table) {
            // DDL: InspectionID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('inspection_id');

            // DDL: DriverID bigint NOT NULL FK → drivers (FK_PTI_Driver, no cascade)
            $table->unsignedBigInteger('driver_id');

            // DDL: VehicleID bigint NOT NULL FK → vehicles (FK_PTI_Vehicle, no cascade)
            $table->unsignedBigInteger('vehicle_id');

            // DDL: InspectionTs datetimeoffset(3) DEFAULT sysdatetimeoffset() NOT NULL
            $table->timestampTz('inspection_ts', 3)->useCurrent();

            // DDL: OdometerReading decimal(12,2) NOT NULL — CHECK >= 0
            $table->decimal('odometer_reading', 12, 2);

            // DDL: FuelLevel tinyint NOT NULL — CHECK 0–100
            $table->unsignedTinyInteger('fuel_level');

            // DDL: TiresOk bit DEFAULT 0 NOT NULL
            $table->boolean('tires_ok')->default(false);

            // DDL: BrakesOk bit DEFAULT 0 NOT NULL
            $table->boolean('brakes_ok')->default(false);

            // DDL: LightsOk bit DEFAULT 0 NOT NULL
            $table->boolean('lights_ok')->default(false);

            // DDL: FluidsOk bit DEFAULT 0 NOT NULL
            $table->boolean('fluids_ok')->default(false);

            // DDL: IsSuccess AS (CONVERT([bit], CASE WHEN all checks pass THEN 1 ELSE 0 END)) PERSISTED
            // TODO: Adjust storedAs() expression syntax for your DB engine.
            //       MySQL equivalent shown below.
            $table->boolean('is_success')
                  ->storedAs('CASE WHEN tires_ok = 1 AND brakes_ok = 1 AND lights_ok = 1 AND fluids_ok = 1 THEN 1 ELSE 0 END')
                  ->nullable();

            // FK_PTI_Driver → drivers.driver_id (no cascade in DDL)
            $table->foreign('driver_id')->references('driver_id')->on('drivers');

            // FK_PTI_Vehicle → vehicles.vehicle_id (no cascade in DDL)
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_trip_inspections');
    }
};
