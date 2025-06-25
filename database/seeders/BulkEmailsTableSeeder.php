<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulkEmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bulk_emails')->insert([
            [
                'email' => 'ictmgr@kijabehospital.org',
                'name' => 'Timothy Makori',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'hospitalitymgr@kijabehospital.org',
                'name' => 'Isaac Kenge',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
