<?php

/**
 * Migration: create_inventory_table
 * DDL Source: FleetOpsDB.dbo.Inventory
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
        Schema::create('inventory', function (Blueprint $table) {
            // DDL: PartId int IDENTITY(1,1) — PK
            $table->increments('part_id');

            // DDL: PartName nvarchar(150) NOT NULL
            $table->string('part_name', 150);

            // DDL: OemNumber nvarchar(80) UNIQUE NULL
            $table->string('oem_number', 80)->unique()->nullable();

            // DDL: ServiceType nvarchar(30) NULL
            $table->string('service_type', 30)->nullable();

            // DDL: CompatibleModels nvarchar(MAX) NULL — JSON validated by CK_Inv_Models
            $table->json('compatible_models')->nullable();

            // DDL: Quantity int DEFAULT 0 NOT NULL — CHECK >= 0
            $table->unsignedInteger('quantity')->default(0);

            // DDL: Cost decimal(10,2) DEFAULT 0 NOT NULL
            $table->decimal('cost', 10, 2)->default(0);

            // DDL: CreatedAt datetimeoffset(3) DEFAULT sysdatetimeoffset()
            $table->timestampTz('created_at', 3)->useCurrent();

            // DDL: UpdatedAt datetimeoffset(3) DEFAULT sysdatetimeoffset()
            $table->timestampTz('updated_at', 3)->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
