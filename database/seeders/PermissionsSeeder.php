<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('auth.defaults.guard', 'web');

        $groups = [
            'roles'              => ['view', 'create', 'update', 'delete'],
            'platform_managers'  => ['view', 'create', 'update', 'delete'],
            'site_users'         => ['view', 'create', 'update', 'delete'],
            'clients'            => ['view', 'create', 'update', 'delete'],
        ];

        $allPermissionNames = [];
        foreach ($groups as $group => $actions) {
            foreach ($actions as $action) {
                $name = "{$group}.{$action}";
                $allPermissionNames[] = $name;

                Permission::firstOrCreate([
                    'name'       => $name,
                    'guard_name' => $guard,
                ]);
            }
        }

        $adminRole = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => $guard,
        ]);

        $adminRole->syncPermissions(
            Permission::whereIn('name', $allPermissionNames)->get()
        );

        $managerEmail = 'dev@admin.com';
        if ($user = User::where('email', $managerEmail)->first()) {
            $user->syncRoles(['admin']);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
