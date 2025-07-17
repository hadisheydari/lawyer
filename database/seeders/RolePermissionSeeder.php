<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'read drivers',
            'write drivers',
            'read setting',
            'write setting',
            'read vehicles',
            'write vehicles',
            'read cargo list',
            'write cargo list',
            'Reserve',
            'bids',
            'read cargo declaration',
            'write cargo declaration',
            'read cargo delivery',
            'write cargo delivery',
            'read complaints',
            'write complaints',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $companyRole = Role::firstOrCreate(['name' => 'Company']);
        $driverRole = Role::firstOrCreate(['name' => 'Driver']);
        $productOwnerRole = Role::firstOrCreate(['name' => 'Product Owner']);

        $companyRole->syncPermissions([
            'manage users',
            'read drivers',
            'write drivers',
            'read setting',
            'write setting',
            'read vehicles',
            'write vehicles',
            'read cargo list',
            'write cargo list',
            'Reserve',
            'bids',
            'read cargo declaration',
            'read cargo delivery',
            'write cargo delivery',
            'read complaints',
            'write complaints',
            ]);
        $driverRole->syncPermissions([
            'manage users',
            'read setting',
            'read vehicles',
            'read cargo list',
            'read cargo declaration',
            'read cargo delivery',
            'read complaints',
            'write complaints',
            ]);
        $productOwnerRole->syncPermissions([
            'manage users',
            'read cargo list',
            'write cargo list',
            'Reserve',
            'bids',
            'read cargo declaration',
            'write cargo declaration',
            'read cargo delivery',
            'read complaints',
            'write complaints',
        ]);
    }
}
