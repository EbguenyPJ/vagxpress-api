<?php

namespace App\Utils;

class CarpetasImagenes
{
    // Cambiar entre 'dev' y 'prod'
    private static $env = 'dev';

    public static function getFolder($key)
    {
        $paths = [
            'dev' => [
                'entrega'                   => 'TallerUpDev/img_entrega',
                'evidencias_clientes'       => 'TallerUpDev/img_evidencias_clientes',
                'qr_citas'                  => 'TallerUpDev/img_qr_citas',
                'recepcion'                 => 'TallerUpDev/img_recepcion',
                'servicio'                  => 'TallerUpDev/img_servicio',
                'principal'                 => 'TallerUpDev/img_principales_vehiculos',
                'refacciones'               => 'TallerUpDev/img_refacciones',
                'otros'                     => 'TallerUpDev/otros',
            ],
            'prod' => [
                'entrega'                   => 'TallerUp/img_entrega',
                'evidencias_clientes'       => 'TallerUp/img_evidencias_clientes',
                'qr_citas'                  => 'TallerUp/img_qr_citas',
                'recepcion'                 => 'TallerUp/img_recepcion',
                'servicio'                  => 'TallerUp/img_servicio',
                'principal'                 => 'TallerUp/img_principales_vehiculos',
                'refacciones'               => 'TallerUpDev/img_refacciones',
                'otros'                     => 'TallerUp/otros',
            ],
        ];

        return $paths[self::$env][$key] ?? null;
    }
}
