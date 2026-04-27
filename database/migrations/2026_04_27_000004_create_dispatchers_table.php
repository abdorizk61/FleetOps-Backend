<?php

/**
 * Migration: create_dispatchers_table
 * DDL Source: FleetOpsDB.dbo.Dispatcher
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
        Schema::create('dispatchers', function (Blueprint $table) {
            // DDL: DispatcherID bigint NOT NULL — PK IS the FK (no auto-increment)
            $table->unsignedBigInteger('dispatcher_id');
            $table->primary('dispatcher_id');

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL (no UpdatedAt in DDL)
            $table->dateTime('created_at')->nullable();

            // DDL: FK__Dispatche__Dispa → users.user_id ON DELETE CASCADE
            $table->foreign('dispatcher_id')
                  ->references('user_id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatchers');
    }
};
