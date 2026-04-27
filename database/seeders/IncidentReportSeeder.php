<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * IncidentReportSeeder — بلاغات حوادث حقيقية
 */
class IncidentReportSeeder extends Seeder
{
    public function run(): void
    {
        $drivers  = DB::table('drivers')->pluck('driver_id')->toArray();
        $vehicles = DB::table('vehicles')->pluck('vehicle_id')->toArray();

        if (empty($drivers) || empty($vehicles)) {
            $this->command->warn('⚠️  IncidentReportSeeder: No drivers or vehicles found.');
            return;
        }

        $reports = [
            [
                'driver_id'   => $drivers[1] ?? $drivers[0],
                'vehicle_id'  => $vehicles[1] ?? $vehicles[0],
                'type'        => 'breakdown',
                'severity'    => 'high',
                'description' => 'Vehicle broke down on the Cairo–Alex desert road at km 87. Engine failure suspected. Tow truck requested.',
                'latitude'    => 30.0561,
                'longitude'   => 31.2394,
                'photo_urls'  => json_encode(['https://storage.fleetops.com/incidents/inc-001-a.jpg']),
                'incident_ts' => '2026-04-27 11:45:00',
            ],
            [
                'driver_id'   => $drivers[0],
                'vehicle_id'  => $vehicles[0],
                'type'        => 'traffic_violation',
                'severity'    => 'low',
                'description' => 'Driver received a fine for illegal parking in a loading zone near Nasr City warehouse.',
                'latitude'    => 30.0626,
                'longitude'   => 31.3417,
                'photo_urls'  => null,
                'incident_ts' => '2026-04-26 14:20:00',
            ],
            [
                'driver_id'   => $drivers[2] ?? $drivers[0],
                'vehicle_id'  => $vehicles[2] ?? $vehicles[0],
                'type'        => 'cargo_damage',
                'severity'    => 'medium',
                'description' => 'Two fragile packages (QR-10030001, QR-10030002) damaged due to sudden braking. Customer notified.',
                'latitude'    => 30.0131,
                'longitude'   => 31.2089,
                'photo_urls'  => json_encode([
                    'https://storage.fleetops.com/incidents/inc-003-a.jpg',
                    'https://storage.fleetops.com/incidents/inc-003-b.jpg',
                ]),
                'incident_ts' => '2026-04-27 09:10:00',
            ],
        ];

        DB::table('incident_reports')->insert($reports);

        $this->command->info('✅ IncidentReportSeeder: ' . count($reports) . ' incident reports ready.');
    }
}
