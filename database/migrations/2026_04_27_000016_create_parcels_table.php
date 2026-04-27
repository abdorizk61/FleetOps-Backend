<?php

/**
 * Migration: create_parcels_table
 * DDL Source: FleetOpsDB.dbo.Parcel
 * Execution Tier: 4 — FK → orders, drivers
 *
 * NOTE: [Order_ID(FK)] and [Driver_ID(FK)] column names were normalized to order_id / driver_id.
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
        Schema::create('parcels', function (Blueprint $table) {
            // DDL: Parcel_ID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('parcel_id');

            // DDL: [Order_ID(FK)] bigint NULL — normalized to order_id
            $table->unsignedBigInteger('order_id')->nullable();

            // DDL: [Driver_ID(FK)] bigint NULL — normalized to driver_id
            $table->unsignedBigInteger('driver_id')->nullable();

            // DDL: Price int NOT NULL
            $table->integer('price');

            // DDL: category nvarchar(50) NULL
            $table->string('category', 50)->nullable();

            // DDL: QRCode nvarchar(100) NULL
            $table->string('qr_code', 100)->nullable();

            // DDL: Status nvarchar(50) NULL
            $table->string('status', 50)->nullable();

            // DDL: Weight nchar(10) NULL
            $table->string('weight', 10)->nullable();

            // DDL: CreatedAt datetime2 NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();

            // FK_Parcels_Orders → orders.order_id (no cascade in DDL)
            $table->foreign('order_id')->references('order_id')->on('orders')->nullOnDelete();

            // FK_Parcels_Drivers → drivers.driver_id (no cascade in DDL)
            $table->foreign('driver_id')->references('driver_id')->on('drivers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
