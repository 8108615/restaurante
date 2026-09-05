<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
        ]);

        User::create([
            'name' => 'Erick Fernando Morales Gil',
            'email' => 'erick@gmail.com',
            'foto_perfil' => null,
            'estado' => 'Activo',
            'password' => bcrypt('12345678'),
        ])->assignRole('SUPER ADMIN');

        Ajuste::create([
            'nombre' => 'Restaurante',
            'descripcion' => 'Sistema de Restaurante',
            'direccion' => 'Av Cumavi',
            'telefono' => '76658532',
            'email' => 'restaurante@gmail.com',
            'divisa' => 'BOB',
            'logo' => null,
            'web' => 'https://www.restaurante.com',
        ]);
    }
}
