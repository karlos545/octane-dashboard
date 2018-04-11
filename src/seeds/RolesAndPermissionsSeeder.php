<?php

namespace Octane\Seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public $adminPermissions = [
        'create',
        'read',
        'update',
        'delete'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        foreach ($this->adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $role = Role::firstOrCreate(['name' => 'admin']);
        $this->attachPermissionsToRole($role);

        $role = Role::firstOrCreate(['name' => 'superadmin']);
        $this->attachPermissionsToRole($role);
    }

    protected function attachPermissionsToRole($role)
    {
        collect($this->adminPermissions)->each(function ($permission) use ($role) {
            ! $role->hasPermissionTo($permission) ? $role->givePermissionTo($permission) : '';
        });
    }
}
