<?php

/**
 * Migration: create_fleet_managers_table
 * DDL Source: FleetOpsDB.dbo.FleetManager
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
        Schema::create('fleet_managers', function (Blueprint $table) {
            // DDL: FleetManagerID bigint NOT NULL — PK IS the FK (no auto-increment)
            $table->unsignedBigInteger('fleet_manager_id');
            $table->primary('fleet_manager_id');

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();

            // DDL: FK__FleetMana__Fleet → users.user_id ON DELETE CASCADE
            $table->foreign('fleet_manager_id')
                  ->references('user_id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_managers');
    }
};
