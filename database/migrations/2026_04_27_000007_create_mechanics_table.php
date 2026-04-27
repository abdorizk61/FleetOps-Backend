<?php

/**
 * Migration: create_mechanics_table
 * DDL Source: FleetOpsDB.dbo.Mechanic
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
        Schema::create('mechanics', function (Blueprint $table) {
            // DDL: MechanicID bigint NOT NULL — PK IS the FK (no auto-increment)
            $table->unsignedBigInteger('mechanic_id');
            $table->primary('mechanic_id');

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();

            // DDL: FK__Mechanics__Mecha → users.user_id ON DELETE CASCADE
            $table->foreign('mechanic_id')
                  ->references('user_id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanics');
    }
};
