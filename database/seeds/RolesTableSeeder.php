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

        Permission::create(["name" => "tickets.*"]);
        Permission::create(["name" => "users.*"]);
        Permission::create(["name" => "events.view"]);
        Permission::create(["name" => "reservations.bypass"]);
        Permission::create(["name" => "users.create"]);
        Permission::create(["name" => "tickets.view"]);
        Permission::create(["name" => "editRole"]);
        Permission::create(["name" => "*"]);


        Role::create(['name' => 'user']);
        Role::create(['name' => 'deacon']);

        $deaconAdmin = Role::create(['name' => 'deacon-admin']);
        $deaconAdmin->givePermissionTo('users.*');
        $deaconAdmin->givePermissionTo('editRole');
        $deaconAdmin->givePermissionTo('tickets.*');

        $role = Role::create(['name' => 'super-admin']);

        $role->givePermissionTo("*");

        $kashafa = Role::create(['name' => 'kashafa']);
        $kashafa->givePermissionTo("tickets.view");

        $agent = Role::create(['name' => 'agent']);
        $agent->givePermissionTo("tickets.*", "users.*", "events.view");
    }
}
