<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Notification\Models\Notification;
use App\Modules\AuthIdentity\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::limit(20)->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->user_id,
                'channel' => 'push',
                'event_type' => 'status_update',
                'payload' => [
                    'title' => 'Welcome to FleetOps',
                    'body'  => "Hello {$user->name}, your account is ready.",
                ],
                'status' => 'sent',
                'dedup_key' => Str::uuid(),
                'retry_count' => 0,
                'sent_at' => Carbon::now(),
                'delivered_at' => Carbon::now(),
            ]);
        }
    }
}
