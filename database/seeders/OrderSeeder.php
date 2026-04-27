<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OrderSeeder — طلبات توصيل حقيقية + parcels مترابطة
 * orders.order_id ليس IDENTITY — لازم نحدده يدوياً
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $drivers   = DB::table('drivers')->pluck('driver_id')->toArray();
        $customers = DB::table('customers')->pluck('customer_id')->toArray();

        if (empty($drivers) || empty($customers)) {
            $this->command->warn('⚠️  OrderSeeder: No drivers or customers found.');
            return;
        }

        $orders = [
            [
                'order_id'            => 1001,
                'driver_id'           => $drivers[0],
                'customer_id'         => $customers[0],
                'status'              => 'Assigned',
                'eta'                 => '10:30',
                'delivery_time'       => '2026-04-28 10:30:00',
                'priority'            => 'High',
                'price'               => 850,
                'digital_signature'   => 'SIG-A001',
                'delivery_preference' => 'Morning',
                'payment_method'      => 'Cash',
            ],
            [
                'order_id'            => 1002,
                'driver_id'           => $drivers[1],
                'customer_id'         => $customers[1],
                'status'              => 'InProgress',
                'eta'                 => '14:00',
                'delivery_time'       => '2026-04-28 14:00:00',
                'priority'            => 'Medium',
                'price'               => 1200,
                'digital_signature'   => null,
                'delivery_preference' => 'Afternoon',
                'payment_method'      => 'Card',
            ],
            [
                'order_id'            => 1003,
                'driver_id'           => $drivers[2],
                'customer_id'         => $customers[2],
                'status'              => 'Pending',
                'eta'                 => '09:00',
                'delivery_time'       => '2026-04-29 09:00:00',
                'priority'            => 'Low',
                'price'               => 450,
                'digital_signature'   => null,
                'delivery_preference' => 'Morning',
                'payment_method'      => 'Cash',
            ],
            [
                'order_id'            => 1004,
                'driver_id'           => $drivers[0],
                'customer_id'         => $customers[3] ?? $customers[0],
                'status'              => 'Assigned',
                'eta'                 => '16:00',
                'delivery_time'       => '2026-04-29 16:00:00',
                'priority'            => 'High',
                'price'               => 3200,
                'digital_signature'   => 'SIG-A004',
                'delivery_preference' => 'Any',
                'payment_method'      => 'Card',
            ],
            [
                'order_id'            => 1005,
                'driver_id'           => $drivers[1],
                'customer_id'         => $customers[0],
                'status'              => 'Cancelled',
                'eta'                 => null,
                'delivery_time'       => null,
                'priority'            => 'Low',
                'price'               => 300,
                'digital_signature'   => null,
                'delivery_preference' => 'Morning',
                'payment_method'      => 'Cash',
            ],
        ];

        foreach ($orders as $o) {
            $exists = DB::table('orders')->where('order_id', $o['order_id'])->exists();
            if (!$exists) {
                DB::table('orders')->insert(array_merge($o, ['created_at' => now()]));
            }
        }

        // ─── Parcels ──────────────────────────────────────────────────────────
        $parcels = [
            ['order_id' => 1001, 'driver_id' => $drivers[0], 'price' => 280, 'category' => 'Electronics',  'qr_code' => 'QR-10010001', 'status' => 'InTransit', 'weight' => '2.5 kg'],
            ['order_id' => 1001, 'driver_id' => $drivers[0], 'price' => 570, 'category' => 'Clothing',     'qr_code' => 'QR-10010002', 'status' => 'InTransit', 'weight' => '4.0 kg'],
            ['order_id' => 1002, 'driver_id' => $drivers[1], 'price' => 1200,'category' => 'Furniture',    'qr_code' => 'QR-10020001', 'status' => 'InTransit', 'weight' => '18 kg'],
            ['order_id' => 1003, 'driver_id' => $drivers[2], 'price' => 250, 'category' => 'Pharmaceuticals','qr_code' => 'QR-10030001','status' => 'Pending',   'weight' => '1.2 kg'],
            ['order_id' => 1003, 'driver_id' => $drivers[2], 'price' => 200, 'category' => 'Pharmaceuticals','qr_code' => 'QR-10030002','status' => 'Pending',   'weight' => '0.8 kg'],
            ['order_id' => 1004, 'driver_id' => $drivers[0], 'price' => 3200,'category' => 'Machinery',    'qr_code' => 'QR-10040001', 'status' => 'Pending',   'weight' => '95 kg'],
        ];

        foreach ($parcels as $p) {
            $exists = DB::table('parcels')->where('qr_code', $p['qr_code'])->exists();
            if (!$exists) {
                DB::table('parcels')->insert(array_merge($p, ['created_at' => now()]));
            }
        }

        $this->command->info('✅ OrderSeeder: ' . count($orders) . ' orders + ' . count($parcels) . ' parcels ready.');
    }
}
