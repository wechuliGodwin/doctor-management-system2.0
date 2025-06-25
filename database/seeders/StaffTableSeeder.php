<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff::create([
            'name' => 'ICT Manager',
            'email' => 'ictmgr@kijabehospital.org',
            'password' => Hash::make('Kijabe@2024'),
        ]);
    }
}
