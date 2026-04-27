<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder — يملأ جدول users بحسابات حقيقية لكل الأدوار
 * idempotent: updateOrCreate على email
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            // ─── Fleet Managers ───────────────────────────────────────────────
            ['name' => 'Khalid Hassan',       'email' => 'khalid@fleetops.com',      'phone_no' => '01012345001', 'role' => 'FleetManager'],
            ['name' => 'Samira Nour',         'email' => 'samira@fleetops.com',      'phone_no' => '01012345002', 'role' => 'FleetManager'],

            // ─── Dispatchers ──────────────────────────────────────────────────
            ['name' => 'Youssef Ibrahim',     'email' => 'youssef@fleetops.com',     'phone_no' => '01012345003', 'role' => 'Dispatcher'],
            ['name' => 'Nada Farouk',         'email' => 'nada@fleetops.com',        'phone_no' => '01012345004', 'role' => 'Dispatcher'],

            // ─── Drivers ──────────────────────────────────────────────────────
            ['name' => 'Ahmed Sayed',         'email' => 'ahmed.driver@fleetops.com',  'phone_no' => '01012345005', 'role' => 'Driver'],
            ['name' => 'Omar Tarek',          'email' => 'omar.driver@fleetops.com',   'phone_no' => '01012345006', 'role' => 'Driver'],
            ['name' => 'Hassan Mahmoud',      'email' => 'hassan.driver@fleetops.com', 'phone_no' => '01012345007', 'role' => 'Driver'],
            ['name' => 'Karim Adel',          'email' => 'karim.driver@fleetops.com',  'phone_no' => '01012345008', 'role' => 'Driver'],
            ['name' => 'Mostafa Ali',         'email' => 'mostafa.driver@fleetops.com','phone_no' => '01012345009', 'role' => 'Driver'],

            // ─── Mechanics ────────────────────────────────────────────────────
            ['name' => 'Mohamed Mechanic',    'email' => 'mohamed.mech@fleetops.com',  'phone_no' => '01012345010', 'role' => 'Mechanic'],
            ['name' => 'Ibrahim Saad',        'email' => 'ibrahim.mech@fleetops.com',  'phone_no' => '01012345011', 'role' => 'Mechanic'],

            // ─── Customers ────────────────────────────────────────────────────
            ['name' => 'Delta Logistics Co.', 'email' => 'delta@client.com',           'phone_no' => '01012345012', 'role' => 'Customer'],
            ['name' => 'Cairo Retail Group',  'email' => 'cairo.retail@client.com',    'phone_no' => '01012345013', 'role' => 'Customer'],
            ['name' => 'Nile Express Ltd.',   'email' => 'nile.express@client.com',    'phone_no' => '01012345014', 'role' => 'Customer'],
            ['name' => 'AlexPort Trading',    'email' => 'alexport@client.com',        'phone_no' => '01012345015', 'role' => 'Customer'],
        ];

        foreach ($users as $u) {
            \App\Modules\AuthIdentity\Models\User::updateOrCreate(
                ['email' => $u['email']],
                array_merge($u, [
                    'password'  => Hash::make('Fleet@2026!'),
                    'is_active' => true,
                    'created_at'=> $now,
                    'updated_at'=> $now,
                ])
            );
        }

        $this->command->info('✅ UserSeeder: ' . count($users) . ' users ready.');
    }
}