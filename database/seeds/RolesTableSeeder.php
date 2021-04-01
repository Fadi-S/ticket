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

        $user = Role::create(['name' => 'user']);
        Permission::create(["name" => "users.create"]);
        $user->givePermissionTo("users.create");

        $role = Role::create(['name' => 'super-admin']);
        Permission::create(["name" => "*"]);
        $role->givePermissionTo("*");

        $kashafa = Role::create(['name' => 'kashafa']);
        Permission::create(["name" => "tickets.view"]);
        $kashafa->givePermissionTo("tickets.view");

        $agent = Role::create(['name' => 'agent']);
        Permission::create(["name" => "tickets.*"]);
        Permission::create(["name" => "users.*"]);
        Permission::create(["name" => "events.view"]);
        Permission::create(["name" => "reservations.bypass"]);
        $agent->givePermissionTo("tickets.*", "users.*", "events.view");
    }
}
