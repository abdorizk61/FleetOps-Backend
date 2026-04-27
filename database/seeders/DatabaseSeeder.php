<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ترتيب الـ FK dependencies مهم جداً
        $this->call([
            UserSeeder::class,               // Tier 0: users
            VehicleSeeder::class,            // Tier 0: vehicles
            InventorySeeder::class,          // Tier 0: inventory
            ProfileSeeder::class,            // Tier 1: customers, drivers, dispatchers, fleet_managers, mechanics
            MaintenanceAssignmentSeeder::class, // Tier 2: maintenance_assignments
            RouteSeeder::class,              // Tier 2: routes
            OrderSeeder::class,              // Tier 3: orders + parcels
            FuelAuditLogSeeder::class,       // Tier 1: fuel_audit_logs
            IncidentReportSeeder::class,     // Tier 2: incident_reports
        ]);
    }
}
