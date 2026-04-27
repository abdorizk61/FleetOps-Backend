<?php

/**
 * Migration: create_routes_table
 * DDL Source: FleetOpsDB.dbo.Route
 * Execution Tier: 2 — FK → drivers, dispatchers, vehicles
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            // DDL: RouteID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('route_id');

            // DDL: RouteName nvarchar(200) NOT NULL
            $table->string('route_name', 200);

            // DDL: DriverID bigint NULL FK → drivers
            $table->unsignedBigInteger('driver_id')->nullable();

            // DDL: DispatcherID bigint NULL FK → dispatchers
            $table->unsignedBigInteger('dispatcher_id')->nullable();

            // DDL: VehicleID bigint NULL FK → vehicles
            $table->unsignedBigInteger('vehicle_id')->nullable();

            // DDL: ScheduledStartTime datetime2 NULL
            $table->dateTime('scheduled_start_time')->nullable();

            // DDL: ActualStartTime datetime2 NULL
            $table->dateTime('actual_start_time')->nullable();

            // DDL: ScheduledEndTime datetime2 NULL
            $table->dateTime('scheduled_end_time')->nullable();

            // DDL: Status nvarchar(30) DEFAULT 'Planned' NULL
            $table->string('status', 30)->default('Planned')->nullable();

            // DDL: TotalDistance decimal(10,2) NULL
            $table->decimal('total_distance', 10, 2)->nullable();

            // DDL: TotalStops int NULL
            $table->integer('total_stops')->nullable();

            // DDL: FuelConsumptionEst decimal(8,2) NULL
            $table->decimal('fuel_consumption_est', 8, 2)->nullable();

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL
            $table->dateTime('created_at')->nullable();

            // القيود الخارجية FK (تم تعديلها لتوافق SQL Server)
            // تم استخدام noActionOnDelete() بدلاً من nullOnDelete لتجنب تعارض مسارات الحذف
            $table->foreign('driver_id')->references('driver_id')->on('drivers')->noActionOnDelete();
            $table->foreign('dispatcher_id')->references('dispatcher_id')->on('dispatchers')->noActionOnDelete();
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->noActionOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};