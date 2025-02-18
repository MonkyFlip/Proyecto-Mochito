<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar 10 registros con el dominio @mony-tek.com
        for ($i = 0; $i < 20; $i++) {
            DB::table('tb_usuarios')->insert([
                'nombre' => Str::random(10),
                'email' => Str::random(10) . '@mony-tek.com',
                'password' => bcrypt('password'),
                'id_rol' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Dominios variados
        $domains = ['@example.com', '@gmail.com', '@outlook.com', '@yahoo.com'];

        // Generar 1000 registros con dominios variados
        for ($i = 0; $i < 1000; $i++) {
            DB::table('tb_usuarios')->insert([
                'nombre' => Str::random(10),
                'email' => Str::random(10) . $domains[array_rand($domains)],
                'password' => bcrypt('password'),
                'id_rol' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
