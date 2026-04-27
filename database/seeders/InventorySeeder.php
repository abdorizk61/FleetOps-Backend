<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * InventorySeeder — قطع غيار حقيقية في المستودع
 */
class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $parts = [
            ['part_name' => 'Engine Oil Filter',      'oem_number' => 'OEM-OF-001', 'service_type' => 'oil_change',    'compatible_models' => ['Toyota Hilux', 'Isuzu D-Max'],               'quantity' => 24,  'cost' => 45.00],
            ['part_name' => 'Air Filter',             'oem_number' => 'OEM-AF-002', 'service_type' => 'inspection',    'compatible_models' => ['Ford Transit', 'Mercedes Sprinter'],           'quantity' => 18,  'cost' => 120.00],
            ['part_name' => 'Brake Pads (Front)',     'oem_number' => 'OEM-BP-003', 'service_type' => 'brake_service', 'compatible_models' => ['Hyundai H350', 'Nissan Urvan'],                'quantity' => 12,  'cost' => 350.00],
            ['part_name' => 'Brake Discs (Rear)',     'oem_number' => 'OEM-BD-004', 'service_type' => 'brake_service', 'compatible_models' => ['Ford Transit', 'Mercedes Sprinter'],           'quantity' => 8,   'cost' => 620.00],
            ['part_name' => 'Alternator Belt',        'oem_number' => 'OEM-AB-005', 'service_type' => 'engine_repair', 'compatible_models' => ['Mitsubishi Fuso', 'MAN TGS'],                  'quantity' => 10,  'cost' => 85.00],
            ['part_name' => 'Fuel Injector',          'oem_number' => 'OEM-FI-006', 'service_type' => 'engine_repair', 'compatible_models' => ['Mercedes Sprinter', 'Mitsubishi Fuso'],        'quantity' => 6,   'cost' => 1200.00],
            ['part_name' => 'Transmission Fluid',     'oem_number' => 'OEM-TF-007', 'service_type' => 'transmission',  'compatible_models' => ['MAN TGS', 'Mitsubishi Fuso'],                  'quantity' => 30,  'cost' => 95.00],
            ['part_name' => 'Radiator Coolant 5L',    'oem_number' => 'OEM-RC-008', 'service_type' => 'other',         'compatible_models' => ['Toyota Hilux', 'Isuzu D-Max', 'Nissan Urvan'], 'quantity' => 40,  'cost' => 55.00],
            ['part_name' => 'Spark Plug Set',         'oem_number' => 'OEM-SP-009', 'service_type' => 'engine_repair', 'compatible_models' => ['Nissan Urvan'],                                'quantity' => 20,  'cost' => 180.00],
            ['part_name' => 'Tire 215/70 R15',        'oem_number' => 'OEM-TR-010', 'service_type' => 'tire_rotation', 'compatible_models' => ['Toyota Hilux', 'Hyundai H350', 'Nissan Urvan'],'quantity' => 16,  'cost' => 850.00],
            ['part_name' => 'Battery 12V 100Ah',      'oem_number' => 'OEM-BT-011', 'service_type' => 'electrical',   'compatible_models' => ['Ford Transit', 'Hyundai H350'],                 'quantity' => 5,   'cost' => 900.00],
            ['part_name' => 'Headlight Assembly',     'oem_number' => 'OEM-HL-012', 'service_type' => 'electrical',   'compatible_models' => ['Mercedes Sprinter'],                            'quantity' => 4,   'cost' => 1500.00],
        ];

        foreach ($parts as $p) {
            DB::table('inventory')->updateOrInsert(
                ['oem_number' => $p['oem_number']],
                array_merge($p, [
                    'compatible_models' => json_encode($p['compatible_models']),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ])
            );
        }

        $this->command->info('✅ InventorySeeder: ' . count($parts) . ' parts ready.');
    }
}
