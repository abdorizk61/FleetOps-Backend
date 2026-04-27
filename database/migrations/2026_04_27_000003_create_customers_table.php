<?php

/**
 * Migration: create_customers_table
 * DDL Source: FleetOpsDB.dbo.Customer
 * Execution Tier: 1 — FK → users
 * @author Team Leader (Khalid)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            // DDL: CustomerID bigint NOT NULL — PK IS the FK (no auto-increment)
            $table->unsignedBigInteger('customer_id');
            $table->primary('customer_id');

            // DDL: Address nvarchar(500) NULL
            $table->string('address', 500)->nullable();

            // DDL: DeliveryPreference nvarchar(255) NULL
            $table->string('delivery_preference', 255)->nullable();

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();

            // DDL: FK__Customers__Custo → users.user_id ON DELETE CASCADE
            $table->foreign('customer_id')
                  ->references('user_id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
