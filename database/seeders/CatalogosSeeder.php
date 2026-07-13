<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Siembra todos los catálogos del sistema en orden de dependencias.
 */
class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            Catalogos\CategoriasModulosSeeder::class,
            Catalogos\ModulosSeeder::class,
            Catalogos\TiposConfiguracionesSeeder::class,
            Catalogos\PorcentajesUtilidadSeeder::class,
            Catalogos\TiposUsuariosSeeder::class,
            Catalogos\TiposEmpleadosSeeder::class,
            Catalogos\DescripcionesTiposEmpleadosSeeder::class,
            Catalogos\HabilidadesSeeder::class,
            Catalogos\EstadosDisponibilidadSeeder::class,
            Catalogos\ProfesionesSeeder::class,
            Catalogos\GradosEstudiosSeeder::class,
            Catalogos\EstadosRepublicaSeeder::class,
            Catalogos\MunicipiosSeeder::class,
            Catalogos\SucursalesSeeder::class,
            Catalogos\MarcasRefaccionesSeeder::class,
            Catalogos\CategoriasRefaccionesSeeder::class,
            Catalogos\SubcategoriasRefaccionesSeeder::class,
            Catalogos\ClasesRefaccionesSeeder::class,
            Catalogos\EstatusRefaccionesSeeder::class,
            Catalogos\UnidadesMedidaSeeder::class,
            Catalogos\PosicionesVehiculoSeeder::class,
            Catalogos\UbicacionesAlmacenSeeder::class,
            Catalogos\MetodosPagosSeeder::class,
            Catalogos\BancosSeeder::class,
            Catalogos\TiposCuentasSeeder::class,
            Catalogos\CuentasBancariasSeeder::class,
            Catalogos\TiposClientesSeeder::class,
            Catalogos\TiposCreditosSeeder::class,
            Catalogos\EstatusCreditosSeeder::class,
            Catalogos\EstatusVentasSeeder::class,
            Catalogos\TiposRequisicionesSeeder::class,
            Catalogos\EstatusRequisicionesSeeder::class,
            Catalogos\MotivosPedidosSeeder::class,
            Catalogos\PrioridadesSeeder::class,
            Catalogos\EstatusOrdenesComprasSeeder::class,
            Catalogos\CategoriasGastosSeeder::class,
            Catalogos\TiposGastosSeeder::class,
            Catalogos\TiposEvidenciasSeeder::class,
            Catalogos\EstatusEmbarqueSeeder::class,
            Catalogos\EstatusEntradaSeeder::class,
            Catalogos\EstatusOrdenSeeder::class,
            Catalogos\TiposDestinosSeeder::class,
            Catalogos\TipoRutaSeeder::class,
            Catalogos\EstatusCotizacionesSeeder::class,
            Catalogos\VersionesSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
