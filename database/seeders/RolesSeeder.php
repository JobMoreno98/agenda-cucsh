<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $editor = Role::create(['name' => 'editor']);

        Permission::create(['name' => 'editarEventos'])->syncRoles([$editor, $admin]);
        Permission::create(['name' => 'crearEventos'])->syncRoles([$editor, $admin]);
        Permission::create(['name' => 'eliminarEventos'])->syncRoles([$admin]);

        Permission::create(['name' => 'crearAreas'])->syncRoles([$editor, $admin]);
        Permission::create(['name' => 'editarAreas'])->syncRoles([$editor, $admin]);
        Permission::create(['name' => 'eliminarAreas'])->syncRoles([$admin]);

        Permission::create(['name' => 'crearOrganizador'])->syncRoles([$editor, $admin]);
        Permission::create(['name' => 'editarOrganizador'])->syncRoles([$editor, $admin]);
        Permission::create(['name' => 'eliminarOrganizador'])->syncRoles([$admin]);

        Permission::create(['name' => 'crearUsuarios'])->syncRoles([$admin]);
        Permission::create(['name' => 'editarUsuarios'])->syncRoles([$admin]);
        Permission::create(['name' => 'eliminarUsuarios'])->syncRoles([$admin]);
    }
}
