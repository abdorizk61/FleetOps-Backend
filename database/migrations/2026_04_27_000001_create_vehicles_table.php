<?php

/**
 * Migration: create_vehicles_table
 * DDL Source: FleetOpsDB.dbo.Vehicle
 * Execution Tier: 0 (no foreign key dependencies)
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            // DDL: VehicleID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('vehicle_id');

            // DDL: VehicleBrand nvarchar(100) NOT NULL
            $table->string('vehicle_brand', 100);

            // DDL: VehicleLicense nvarchar(50) NOT NULL UNIQUE
            $table->string('vehicle_license', 50)->unique();

            // DDL: MaxWeightCapacity decimal(10,2) NULL
            $table->decimal('max_weight_capacity', 10, 2)->nullable();

            // DDL: FuelType nvarchar(30) NULL
            $table->string('fuel_type', 30)->nullable();

            // DDL: Status nvarchar(30) DEFAULT 'Active' CHECK (Active|Maintenance|Inactive|OutOfService)
            $table->enum('status', ['Active', 'Maintenance', 'Inactive', 'OutOfService'])
                  ->default('Active')
                  ->nullable();

            // DDL: Current_odometer decimal(12,2) NOT NULL CHECK >= 0
            $table->decimal('current_odometer', 12, 2)->default(0);

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
