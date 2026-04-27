<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * VehicleSeeder — أسطول من 8 مركبات حقيقية
 */
class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['vehicle_brand' => 'Toyota Hilux',      'vehicle_license' => 'أ ب ج 1001', 'max_weight_capacity' => 1500.00, 'fuel_type' => 'Diesel',   'status' => 'Active',      'current_odometer' => 45200.00],
            ['vehicle_brand' => 'Isuzu D-Max',       'vehicle_license' => 'أ ب ج 1002', 'max_weight_capacity' => 2000.00, 'fuel_type' => 'Diesel',   'status' => 'Active',      'current_odometer' => 88750.00],
            ['vehicle_brand' => 'Ford Transit',      'vehicle_license' => 'أ ب ج 1003', 'max_weight_capacity' => 3500.00, 'fuel_type' => 'Diesel',   'status' => 'Active',      'current_odometer' => 121300.00],
            ['vehicle_brand' => 'Mercedes Sprinter', 'vehicle_license' => 'أ ب ج 1004', 'max_weight_capacity' => 3500.00, 'fuel_type' => 'Diesel',   'status' => 'Maintenance', 'current_odometer' => 203500.00],
            ['vehicle_brand' => 'Hyundai H350',      'vehicle_license' => 'أ ب ج 1005', 'max_weight_capacity' => 2500.00, 'fuel_type' => 'Diesel',   'status' => 'Active',      'current_odometer' => 67400.00],
            ['vehicle_brand' => 'Mitsubishi Fuso',   'vehicle_license' => 'أ ب ج 1006', 'max_weight_capacity' => 7000.00, 'fuel_type' => 'Diesel',   'status' => 'Active',      'current_odometer' => 155000.00],
            ['vehicle_brand' => 'Nissan Urvan',      'vehicle_license' => 'أ ب ج 1007', 'max_weight_capacity' => 1200.00, 'fuel_type' => 'Gasoline', 'status' => 'Active',      'current_odometer' => 34100.00],
            ['vehicle_brand' => 'MAN TGS',           'vehicle_license' => 'أ ب ج 1008', 'max_weight_capacity' => 18000.00,'fuel_type' => 'Diesel',   'status' => 'OutOfService','current_odometer' => 412000.00],
        ];

        foreach ($vehicles as $v) {
            DB::table('vehicles')->updateOrInsert(
                ['vehicle_license' => $v['vehicle_license']],
                array_merge($v, ['created_at' => now()])
            );
        }

        $this->command->info('✅ VehicleSeeder: ' . count($vehicles) . ' vehicles ready.');
    }
}
