<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BkMessaging;

class BkMessagingSeeder extends Seeder
{
    public function run()
    {
        BkMessaging::create([
            'appointment_id' => 1, // Ensure this exists in appointments
            'messaging_date' => '2025-05-21',
            'urgent_message' => 'Urgent follow-up needed',
            'sender_name' => 'Dr. Smith',
            'sender_department' => 'Cardiology',
            'active' => 1,
        ]);
    }
}
