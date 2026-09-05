<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Roles principales
        $super_admin = Role::create(['name' => 'SUPER ADMIN', 'guard_name' => 'web']);
        $administrador = Role::create(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $cajero = Role::create(['name' => 'CAJERO', 'guard_name' => 'web']);
    }
}
