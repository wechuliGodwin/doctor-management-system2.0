<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeSlot;
use App\Models\Doctor;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example seeding for each doctor
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Add multiple time slots per doctor
            TimeSlot::create([
                'doctor_id' => $doctor->id,
                'slot_time' => '09:00:00',
                'is_booked' => false,
            ]);

            TimeSlot::create([
                'doctor_id' => $doctor->id,
                'slot_time' => '10:00:00',
                'is_booked' => false,
            ]);

            TimeSlot::create([
                'doctor_id' => $doctor->id,
                'slot_time' => '11:00:00',
                'is_booked' => false,
            ]);
        }
    }
}
