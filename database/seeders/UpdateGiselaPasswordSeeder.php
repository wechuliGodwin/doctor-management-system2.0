<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateGiselaPasswordSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')
            ->where('id', 136) // Gisela's user ID
            ->update([
                'password' => Hash::make('Gisella@2025'), // Replace with your desired password
                'updated_at' => now(),
            ]);
    }
}