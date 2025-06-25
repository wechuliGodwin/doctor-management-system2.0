<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $roleAdmin = Role::create(['name' => 'administrator']);
        $roleResearcher = Role::create(['name' => 'researcher']);

        // Create Permissions
        Permission::create(['name' => 'view_researcher_biodata']);
        Permission::create(['name' => 'view_irec_applications']);
        Permission::create(['name' => 'create_irec_application']);

        // Assign Permissions to Roles
        $roleAdmin->givePermissionTo(['view_researcher_biodata', 'view_irec_applications', 'create_irec_application']);
        $roleResearcher->givePermissionTo('create_irec_application');
    }
}
