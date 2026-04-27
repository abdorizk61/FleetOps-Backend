<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * RouteSeeder — مسارات توصيل حقيقية مترابطة مع drivers / dispatchers / vehicles
 */
class RouteSeeder extends Seeder
{
    public function run(): void
    {
        // جلب IDs الفعلية من قاعدة البيانات
        $drivers     = DB::table('drivers')->pluck('driver_id')->toArray();
        $dispatchers = DB::table('dispatchers')->pluck('dispatcher_id')->toArray();
        $vehicles    = DB::table('vehicles')->where('status', 'Active')->pluck('vehicle_id')->toArray();

        if (empty($drivers) || empty($dispatchers) || empty($vehicles)) {
            $this->command->warn('⚠️  RouteSeeder: Missing drivers/dispatchers/vehicles — run ProfileSeeder first.');
            return;
        }

        $routes = [
            [
                'route_name'           => 'Cairo Ring Road — Morning Run',
                'driver_id'            => $drivers[0] ?? null,
                'dispatcher_id'        => $dispatchers[0] ?? null,
                'vehicle_id'           => $vehicles[0] ?? null,
                'scheduled_start_time' => '2026-04-28 07:00:00',
                'actual_start_time'    => '2026-04-28 07:10:00',
                'scheduled_end_time'   => '2026-04-28 13:00:00',
                'status'               => 'Completed',
                'total_distance'       => 187.50,
                'total_stops'          => 6,
                'fuel_consumption_est' => 28.20,
            ],
            [
                'route_name'           => 'Alexandria — Giza Express',
                'driver_id'            => $drivers[1] ?? null,
                'dispatcher_id'        => $dispatchers[0] ?? null,
                'vehicle_id'           => $vehicles[1] ?? null,
                'scheduled_start_time' => '2026-04-28 06:00:00',
                'actual_start_time'    => '2026-04-28 06:05:00',
                'scheduled_end_time'   => '2026-04-28 14:00:00',
                'status'               => 'InProgress',
                'total_distance'       => 220.00,
                'total_stops'          => 4,
                'fuel_consumption_est' => 42.50,
            ],
            [
                'route_name'           => 'Nasr City — 10th Ramadan',
                'driver_id'            => $drivers[2] ?? null,
                'dispatcher_id'        => $dispatchers[1] ?? null,
                'vehicle_id'           => $vehicles[2] ?? null,
                'scheduled_start_time' => '2026-04-29 08:00:00',
                'actual_start_time'    => null,
                'scheduled_end_time'   => '2026-04-29 15:00:00',
                'status'               => 'Planned',
                'total_distance'       => 95.00,
                'total_stops'          => 5,
                'fuel_consumption_est' => 18.00,
            ],
            [
                'route_name'           => 'Giza Distribution Loop',
                'driver_id'            => $drivers[0] ?? null,
                'dispatcher_id'        => $dispatchers[1] ?? null,
                'vehicle_id'           => $vehicles[4] ?? null,
                'scheduled_start_time' => '2026-04-30 09:00:00',
                'actual_start_time'    => null,
                'scheduled_end_time'   => '2026-04-30 17:00:00',
                'status'               => 'Planned',
                'total_distance'       => 145.00,
                'total_stops'          => 8,
                'fuel_consumption_est' => 25.50,
            ],
        ];

        foreach ($routes as $r) {
            // Routes use IDENTITY PK — insert only if not already exists
            $exists = DB::table('routes')->where('route_name', $r['route_name'])->exists();
            if (!$exists) {
                DB::table('routes')->insert(array_merge($r, ['created_at' => now()]));
            }
        }

        $this->command->info('✅ RouteSeeder: ' . count($routes) . ' routes ready.');
    }
}
