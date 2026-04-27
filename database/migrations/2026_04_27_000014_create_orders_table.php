<?php

/**
 * Migration: create_orders_table
 * DDL Source: FleetOpsDB.dbo.[Order]
 * Execution Tier: 3 — FK → customers, drivers
 *
 * NOTE: OrderID in DDL is bigint NOT NULL (no IDENTITY). IDs may be assigned externally.
 * NOTE: [DriverID(FK)] and [CustomerID(FK)] column names were normalized to driver_id / customer_id.
 *
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            // DDL: OrderID bigint NOT NULL — PK (no IDENTITY, externally assigned)
            $table->unsignedBigInteger('order_id');
            $table->primary('order_id');

            // DDL: [DriverID(FK)] bigint NULL — normalized to driver_id
            $table->unsignedBigInteger('driver_id')->nullable();

            // DDL: [CustomerID(FK)] bigint NULL — normalized to customer_id
            $table->unsignedBigInteger('customer_id')->nullable();

            // DDL: Status nvarchar(50) NULL — CK_Orders (Pending|Assigned|InProgress|Cancelled)
            $table->enum('status', ['Pending', 'Assigned', 'InProgress', 'Cancelled'])->nullable();

            // DDL: ETA nchar(10) NULL
            $table->string('eta', 10)->nullable();

            // DDL: Delivery_Time datetime2 NULL
            $table->dateTime('delivery_time')->nullable();

            // DDL: Priority nvarchar(50) NULL
            $table->string('priority', 50)->nullable();

            // DDL: Price int NOT NULL
            $table->integer('price');

            // DDL: digital_signature nchar(10) NULL
            $table->string('digital_signature', 10)->nullable();

            // DDL: Delivery_preference nvarchar(255) NULL
            $table->string('delivery_preference', 255)->nullable();

            // DDL: Payment_method nvarchar(50) NULL
            $table->string('payment_method', 50)->nullable();

            // DDL: Created_at datetime2 NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();

            // FK_Orders_Customers → customers.customer_id
            // تم التغيير لـ noActionOnDelete لتجنب مشاكل SQL Server مع الـ Cascade Paths
            $table->foreign('customer_id')->references('customer_id')->on('customers')->noActionOnDelete();

            // FK_Orders_Drivers → drivers.driver_id
            $table->foreign('driver_id')->references('driver_id')->on('drivers')->noActionOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};