<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesTableSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => 'user']);

        $role = Role::create(['name' => 'super-admin']);
        Permission::create(["name" => "*"]);
        $role->givePermissionTo("*");

        $scanner = Role::create(['name' => 'scanner']);
        Permission::create(["name" => "scan"]);
        Permission::create(["name" => "backend"]);
        $scanner->givePermissionTo("scan");
        $scanner->givePermissionTo("backend");
    }
}
