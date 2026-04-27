<?php

/**
 * Migration: create_users_table
 * DDL Source: FleetOpsDB.dbo.[User]
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
        Schema::create('users', function (Blueprint $table) {
            // DDL: UserID bigint IDENTITY(1,1) — PK
            $table->bigIncrements('user_id');

            // DDL: Email nvarchar(255) NOT NULL UNIQUE
            $table->string('email', 255)->unique();

            // DDL: PasswordHash nvarchar(255) NOT NULL — normalized to 'password' for Laravel Auth
            $table->string('password', 255);

            // DDL: Name nvarchar(200) NOT NULL
            $table->string('name', 200);

            // DDL: PhoneNo nvarchar(50) NULL
            $table->string('phone_no', 50)->nullable();

            // DDL: Role nvarchar(30) CHECK (Customer|Mechanic|Dispatcher|Driver|FleetManager)
            $table->enum('role', ['Customer', 'Mechanic', 'Dispatcher', 'Driver', 'FleetManager']);

            // DDL: IsActive bit DEFAULT 1 NULL
            $table->boolean('is_active')->default(true)->nullable();

            // Laravel Auth requirement (Sanctum)
            $table->rememberToken();

            // DDL: CreatedAt datetime2 DEFAULT getdate() NULL
            $table->dateTime('created_at')->nullable();

            // DDL: UpdatedAt datetime2 DEFAULT getdate() NULL
            $table->dateTime('updated_at')->nullable();
        });

        // Laravel Auth — password reset tokens (framework requirement)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Laravel Auth — sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
