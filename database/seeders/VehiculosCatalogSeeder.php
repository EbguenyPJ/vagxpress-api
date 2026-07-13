<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos demo para el motor de compatibilidad vehicular. Idempotente: puede
 * correrse varias veces sin duplicar. `php artisan db:seed --class=VehiculosCatalogSeeder`
 */
class VehiculosCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // marca => modelo => [ [generación, año_inicio, año_fin], ... ]
        $catalogo = [
            'Ford' => [
                'Lobo'  => [['13va Gen', 2015, 2020], ['14va Gen', 2021, 2024]],
                'F-150' => [['13th Gen', 2015, 2020], ['14th Gen', 2021, 2024]],
            ],
            'Chevrolet' => [
                'Silverado' => [['3ra Gen', 2014, 2018], ['4ta Gen', 2019, 2024]],
            ],
            'Acura' => [
                'TSX'     => [['2da Gen', 2009, 2014]],
                'Integra' => [['Nueva Gen', 2023, 2024]],
            ],
            'Honda' => [
                'Civic'  => [['10ma Gen', 2016, 2021], ['11va Gen', 2022, 2024]],
                'Accord' => [['10ma Gen', 2018, 2022], ['11va Gen', 2023, 2024]],
                'CR-V'   => [['5ta Gen', 2017, 2022], ['6ta Gen', 2023, 2024]],
                'HR-V'   => [['2da Gen', 2016, 2022], ['3ra Gen', 2023, 2024]],
            ],
        ];

        foreach ($catalogo as $marca => $modelos) {
            $idMarca = $this->idDe('tc_marcas_vehiculos', 'id_marca_vehiculo', ['s_marca_vehiculo' => $marca]);

            foreach ($modelos as $modelo => $generaciones) {
                $idModelo = $this->idDe('tc_modelos_vehiculos', 'id_modelo_vehiculo', [
                    'id_marca_vehiculo' => $idMarca,
                    's_modelo_vehiculo' => $modelo,
                ]);

                foreach ($generaciones as [$nombreGen, $inicio, $fin]) {
                    $this->idDe('tc_generaciones', 'id_generacion', [
                        'id_modelo_vehiculo' => $idModelo,
                        's_generacion'       => $nombreGen,
                    ], [
                        'n_anio_inicio' => $inicio,
                        'n_anio_fin'    => $fin,
                    ]);
                }
            }
        }

        $motores = ['5.0L V8', '5.3L V8', '6.2L V8', '3.5L V6', '2.0L Turbo', '1.5L Turbo', '2.4L I4'];
        foreach ($motores as $motor) {
            $this->idDe('tc_motores', 'id_motor', ['s_motor' => $motor]);
        }
    }

    /**
     * Busca una fila por las claves `$match`; si no existe la inserta (con `$extra`).
     * Devuelve la PK. Hace la siembra idempotente.
     */
    private function idDe(string $tabla, string $pk, array $match, array $extra = []): int
    {
        $fila = DB::table($tabla)->where($match)->first();
        if ($fila) {
            return $fila->$pk;
        }

        return DB::table($tabla)->insertGetId(array_merge($match, $extra, [
            'b_activo'   => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }
}
