<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrosIotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registros = [];

        // Generar 100 registros únicos
        for ($i = 0; $i < 100; $i++) {
            $registros[] = [
                'flujo_agua' => rand(1, 10) + rand(0, 99) / 100,
                'nivel_agua' => rand(1, 10) + rand(0, 99) / 100,
                'temp' => rand(15, 30) + rand(0, 99) / 100,
                'energia' => rand(0, 1) ? 'solar' : 'electricidad',
                'id_usuario' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tb_registros_iot')->insert($registros);
    }
}
