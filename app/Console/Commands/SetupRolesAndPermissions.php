<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupRolesAndPermissions extends Command
{
    protected $signature = 'setup:roles-permissions';
    protected $description = 'Set up roles and permissions for the application';

    public function handle()
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

        $this->info('Roles and permissions have been set up.');
    }
}
