<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'researchcoord@kijabehospital.org')->first();

        if ($user) {
            $user->assignRole('administrator');
        } else {
            echo "User not found.";
        }
    }
}
