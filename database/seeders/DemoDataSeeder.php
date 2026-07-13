<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos operativos de demostración para el entorno local: proveedores,
 * clientes, empleados, refacciones con stock, ventas (hoy y días previos),
 * créditos, cotizaciones, requisiciones, órdenes de compra, embarques,
 * gastos, cortes y repartos. Deterministico: mismas filas en cada seed.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $hoy = $now->toDateString();
        $adminId = DB::table('users')->where('name', 'admin')->value('id');

        // ── Proveedores ────────────────────────────────────────────────
        $proveedores = [
            ['s_proveedor' => 'Autopartes del Norte SA', 's_nombre_contacto' => 'Carlos Mendoza', 's_telefono' => '8181234567', 's_rfc' => 'ADN010101AB1'],
            ['s_proveedor' => 'Refacciones Europeas MX', 's_nombre_contacto' => 'Laura Treviño', 's_telefono' => '8187654321', 's_rfc' => 'REM020202CD2'],
            ['s_proveedor' => 'VAG Parts Import', 's_nombre_contacto' => 'Jorge Salinas', 's_telefono' => '5551112233', 's_rfc' => 'VPI030303EF3'],
        ];
        foreach ($proveedores as $p) {
            DB::table('tw_proveedores')->insert($p + ['b_activo' => 1, 'created_at' => $now, 'updated_at' => $now]);
        }

        // ── Clientes ───────────────────────────────────────────────────
        $clientes = [
            ['s_nombre_cliente' => 'Público General', 's_numero_telefono' => '0000000000', 's_correo' => null, 'id_tipo_cliente' => 1, 'b_credito' => 0, 'n_limite_credito' => 0],
            ['s_nombre_cliente' => 'Taller García Hnos', 's_razon_social' => 'Taller García Hermanos SA de CV', 's_rfc' => 'TGH900101XX1', 's_numero_telefono' => '8180001111', 's_correo' => 'compras@tallergarcia.mx', 'id_tipo_cliente' => 2, 'b_credito' => 1, 'n_limite_credito' => 50000],
            ['s_nombre_cliente' => 'María Fernanda López', 's_numero_telefono' => '8182223333', 's_correo' => 'mfl@example.com', 'id_tipo_cliente' => 1, 'b_credito' => 0, 'n_limite_credito' => 0],
            ['s_nombre_cliente' => 'Servicio Automotriz Alfa', 's_razon_social' => 'Servicio Automotriz Alfa SC', 's_rfc' => 'SAA850505YY2', 's_numero_telefono' => '8184445555', 's_correo' => 'alfa@example.com', 'id_tipo_cliente' => 2, 'b_credito' => 1, 'n_limite_credito' => 30000],
            ['s_nombre_cliente' => 'Roberto Cantú', 's_numero_telefono' => '8186667777', 's_correo' => 'rcantu@example.com', 'id_tipo_cliente' => 1, 'b_credito' => 0, 'n_limite_credito' => 0],
        ];
        foreach ($clientes as $c) {
            DB::table('tw_clientes')->insert($c + [
                'n_saldo_actual' => 0,
                'id_usuario_crea' => $adminId,
                'b_activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ── Empleados repartidores / mostrador ─────────────────────────
        $empleados = [
            ['s_nombre' => 'Pedro', 's_apellido_paterno' => 'Ramírez', 's_apellido_materno' => 'Soto', 's_correo' => 'pedro.ramirez@vagxpress.local', 's_telefono' => '8181110001', 'id_tipo_empleado' => 2],
            ['s_nombre' => 'Luis', 's_apellido_paterno' => 'Hernández', 's_apellido_materno' => 'Díaz', 's_correo' => 'luis.hernandez@vagxpress.local', 's_telefono' => '8181110002', 'id_tipo_empleado' => 2],
            ['s_nombre' => 'Ana', 's_apellido_paterno' => 'Torres', 's_apellido_materno' => 'Vega', 's_correo' => 'ana.torres@vagxpress.local', 's_telefono' => '8181110003', 'id_tipo_empleado' => 1],
        ];
        foreach ($empleados as $e) {
            DB::table('tw_empleados')->insert($e + [
                'd_fecha_nacimiento' => '1992-06-15',
                'd_fecha_ingreso' => $now->copy()->subYear()->toDateString(),
                'id_estado_disponibilidad' => 1,
                'id_sucursal' => 1,
                'b_es_usuario' => 0,
                'b_activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Usuario móvil para el repartidor Pedro (selector de repartidores)
        $idPedro = DB::table('tw_empleados')->where('s_nombre', 'Pedro')->value('id_empleado');
        DB::table('users')->insert([
            'name' => 'pedro',
            'email' => 'pedro.ramirez@vagxpress.local',
            'password' => bcrypt('pedro123'),
            's_nombre_completo' => 'Pedro Ramírez Soto',
            's_token' => '',
            'id_empleado' => $idPedro,
            'id_tipo_usuario' => 2,
            'b_usuario_web' => 0,
            'b_usuario_movil' => 1,
            'b_activo' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('tw_empleados')->where('id_empleado', $idPedro)->update(['b_es_usuario' => 1]);

        // ── Refacciones ────────────────────────────────────────────────
        // id_subcategoria válida por categoría, tomada del propio catálogo.
        $subPorCategoria = DB::table('tc_subcategorias_refacciones')
            ->where('b_activo', 1)
            ->get()
            ->groupBy('id_categoria_refaccion');

        $refacciones = [
            // [nombre, numero_parte, categoria, marca, compra, venta, stock, min, max]
            ['Filtro de aceite MANN HU719/7x', 'HU719/7X', 3, 1, 180.00, 260.00, 42, 10, 60],
            ['Filtro de aire K&N 33-2865', '33-2865', 3, 2, 950.00, 1350.00, 8, 5, 20],
            ['Balatas delanteras TRW GDB1550', 'GDB1550', 4, 3, 850.00, 1250.00, 15, 6, 30],
            ['Disco de freno Brembo 09.9772.11', '09.9772.11', 4, 4, 1450.00, 2100.00, 12, 4, 24],
            ['Bujía NGK PFR7S8EG', 'PFR7S8EG', 2, 5, 210.00, 320.00, 64, 16, 100],
            ['Bobina de encendido Beru ZSE057', 'ZSE057', 2, 6, 780.00, 1150.00, 9, 6, 25],
            ['Amortiguador Sachs 314-875', '314-875', 5, 7, 1650.00, 2400.00, 6, 4, 16],
            ['Kit clutch LUK 624-3268-00', '624-3268-00', 6, 8, 4200.00, 6100.00, 3, 2, 8],
            ['Bomba de agua Hepu P652', 'P652', 7, 9, 1100.00, 1600.00, 7, 4, 15],
            ['Termostato Wahler 4264.87D', '4264.87D', 7, 10, 420.00, 610.00, 18, 6, 30],
            ['Alternador Valeo 439565', '439565', 8, 11, 3800.00, 5500.00, 2, 2, 6],
            ['Faro principal Hella 1EL010', '1EL010', 10, 12, 2900.00, 4200.00, 4, 2, 10],
        ];

        $idsRefaccion = [];
        foreach ($refacciones as $i => [$nombre, $parte, $cat, $marca, $compra, $venta, $stock, $min, $max]) {
            $sub = $subPorCategoria->get($cat)?->first();
            $idsRefaccion[] = DB::table('tw_refacciones')->insertGetId([
                's_nombre_refaccion' => $nombre,
                's_descripcion' => 'Refacción de demostración para entorno local',
                's_numero_parte' => $parte,
                's_codigo_qr' => sprintf('QR-DEMO-%04d', $i + 1),
                'n_precio_compra' => $compra,
                'n_precio_venta' => $venta,
                'n_stock_actual' => $stock,
                'n_stock_minimo' => $min,
                'n_stock_maximo' => $max,
                'id_marca_refaccion' => $marca,
                'id_unidad_medida' => 1,
                'id_proveedor' => ($i % 3) + 1,
                'id_categoria_refaccion' => $cat,
                'id_subcategoria_refaccion' => $sub?->id_subcategoria_refaccion,
                'id_ubicacion_almacen' => ($i % 15) + 1,
                'id_estatus_refaccion' => 1,
                'b_importado' => $i % 2,
                'b_activo' => 1,
                'id_usuario_crea' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Relación proveedor-refacción (últimos costos)
        foreach ($idsRefaccion as $i => $idRefaccion) {
            DB::table('tr_proveedores_refacciones')->insert([
                'id_proveedor' => ($i % 3) + 1,
                'id_refaccion' => $idRefaccion,
                'n_ultimo_costo' => $refacciones[$i][4],
                'd_fecha_ultima_compra' => $now->copy()->subDays(20)->toDateString(),
                'id_usuario_crea' => $adminId,
                'b_activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ── Ventas (7 días atrás hasta hoy) ────────────────────────────
        // [días_atras, id_cliente, id_metodo_pago, items => [idx_refaccion, cantidad]]
        $ventas = [
            [6, 1, 2, [[0, 2], [4, 4]]],
            [5, 3, 2, [[2, 1]]],
            [4, 2, 1, [[3, 2], [6, 1]]],          // crédito
            [3, 1, 3, [[1, 1]]],
            [2, 4, 1, [[7, 1]]],                   // crédito
            [1, 5, 2, [[9, 2], [4, 2]]],
            [0, 1, 2, [[0, 3], [2, 1]]],           // hoy
            [0, 3, 5, [[5, 1]]],                   // hoy
            [0, 2, 4, [[8, 1], [10, 1]]],          // hoy
        ];

        foreach ($ventas as [$diasAtras, $idCliente, $idMetodo, $items]) {
            $fecha = $now->copy()->subDays($diasAtras)->setTime(10 + $diasAtras, 30);
            $subtotal = 0;
            $cantidadTotal = 0;
            foreach ($items as [$idx, $cantidad]) {
                $subtotal += $refacciones[$idx][5] * $cantidad;
                $cantidadTotal += $cantidad;
            }
            $subtotal = round($subtotal / 1.16, 2);
            $total = round($subtotal * 1.16, 2);

            $idVenta = DB::table('tw_ventas')->insertGetId([
                'n_subtotal' => $subtotal,
                'n_porcentaje_iva' => 16,
                'n_total' => $total,
                'n_cantidad_refacciones' => $cantidadTotal,
                'id_estatus_venta' => 1, // Pagada
                'id_metodo_pago' => $idMetodo,
                'id_cliente' => $idCliente,
                'id_usuario_crea' => $adminId,
                'b_corte' => $diasAtras > 3 ? 1 : 0, // las viejas ya pasaron por corte
                'b_activo' => 1,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);

            foreach ($items as [$idx, $cantidad]) {
                $precioVenta = $refacciones[$idx][5];
                DB::table('tr_ventas_refacciones')->insert([
                    'id_venta' => $idVenta,
                    'id_refaccion' => $idsRefaccion[$idx],
                    'n_cantidad' => $cantidad,
                    'n_costo_unitario' => $precioVenta,
                    'n_porcentaje_utilidad' => 0,
                    'n_total' => round($precioVenta * $cantidad, 2),
                    'n_stock_previo' => $refacciones[$idx][6],
                    'n_stock_posterior' => $refacciones[$idx][6] - $cantidad,
                    'b_activo' => 1,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
            }

            if ($idMetodo === 1) { // Crédito
                DB::table('tw_creditos')->insert([
                    'id_venta' => $idVenta,
                    'n_total_a_pagar' => $total,
                    'n_total_pagado' => 0,
                    'id_tipo_credito' => 1,
                    'id_estatus_credito' => 1,
                    'id_usuario_crea' => $adminId,
                    'd_fecha_vencimiento' => $fecha->copy()->addDays(30)->toDateString(),
                    'b_activo' => 1,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
                DB::table('tw_clientes')->where('id_cliente', $idCliente)->increment('n_saldo_actual', $total);
            }
        }

        // ── Corte histórico con sus ventas ─────────────────────────────
        $ventasEnCorte = DB::table('tw_ventas')->where('b_corte', 1)->get();
        $fechaCorte = $now->copy()->subDays(4)->setTime(20, 0);
        $idCorte = DB::table('tw_cortes')->insertGetId([
            'id_tipo_corte' => 1,
            'id_usuario_crea' => $adminId,
            'd_fecha_corte' => $fechaCorte,
            'n_monto_efectivo' => $ventasEnCorte->where('id_metodo_pago', 2)->sum('n_total'),
            'n_monto_transferencia' => $ventasEnCorte->where('id_metodo_pago', 5)->sum('n_total'),
            'n_monto_credito' => $ventasEnCorte->where('id_metodo_pago', 1)->sum('n_total'),
            'n_monto_tarjeta_debito' => $ventasEnCorte->where('id_metodo_pago', 4)->sum('n_total'),
            'n_monto_tarjeta_credito' => $ventasEnCorte->where('id_metodo_pago', 3)->sum('n_total'),
            'n_monto_total' => $ventasEnCorte->sum('n_total'),
            's_descripcion_corte' => 'Corte de demostración',
            'b_activo' => 1,
            'created_at' => $fechaCorte,
            'updated_at' => $fechaCorte,
        ]);
        foreach ($ventasEnCorte as $v) {
            DB::table('tr_cortes_ventas')->insert([
                'id_corte' => $idCorte,
                'id_venta' => $v->id_venta,
                'b_activo' => 1,
                'created_at' => $fechaCorte,
                'updated_at' => $fechaCorte,
            ]);
        }

        // ── Cotizaciones ───────────────────────────────────────────────
        foreach ([[2, [[3, 2], [6, 2]]], [4, [[11, 1]]]] as [$idCliente, $items]) {
            $subtotal = 0;
            $cantidadTotal = 0;
            foreach ($items as [$idx, $cantidad]) {
                $subtotal += $refacciones[$idx][5] * $cantidad;
                $cantidadTotal += $cantidad;
            }
            $idCotizacion = DB::table('tw_cotizaciones')->insertGetId([
                'n_subtotal' => round($subtotal, 2),
                'n_porcentaje_iva' => 16,
                'n_total' => round($subtotal * 1.16, 2),
                'n_cantidad_refacciones' => $cantidadTotal,
                'id_cliente' => $idCliente,
                'id_usuario_crea' => $adminId,
                'b_activo' => 1,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ]);
            foreach ($items as [$idx, $cantidad]) {
                DB::table('tr_cotizaciones_refacciones')->insert([
                    'id_cotizacion' => $idCotizacion,
                    'id_refaccion' => $idsRefaccion[$idx],
                    'n_cantidad' => $cantidad,
                    'n_costo_unitario' => $refacciones[$idx][5],
                    'n_porcentaje_utilidad' => 0,
                    'n_total' => round($refacciones[$idx][5] * $cantidad, 2),
                    'b_activo' => 1,
                    'created_at' => $now->copy()->subDays(2),
                    'updated_at' => $now->copy()->subDays(2),
                ]);
            }
        }

        // ── Requisiciones (1 pendiente, 1 en orden de compra) ──────────
        $requisiciones = [
            ['id_estatus_requisicion' => 1, 'items' => [[10, 4], [7, 5]]], // pendiente
            ['id_estatus_requisicion' => 3, 'items' => [[1, 12]]],         // en OC
        ];
        $idsRequisicion = [];
        foreach ($requisiciones as $r) {
            $totalEstimado = 0;
            $cantidadTotal = 0;
            foreach ($r['items'] as [$idx, $cantidad]) {
                $totalEstimado += $refacciones[$idx][4] * $cantidad;
                $cantidadTotal += $cantidad;
            }
            $idRequisicion = DB::table('tw_requisiciones')->insertGetId([
                's_observacion' => 'Requisición de demostración',
                'n_cantidad_refacciones' => $cantidadTotal,
                'n_total_estimado' => round($totalEstimado, 2),
                'd_fecha_solicitud' => $now->copy()->subDays(3)->toDateString(),
                'd_fecha_limite' => $now->copy()->addDays(7)->toDateString(),
                'id_estatus_requisicion' => $r['id_estatus_requisicion'],
                'id_tipo_requisicion' => 1,
                'id_usuario_crea' => $adminId,
                'b_activo' => 1,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ]);
            $idsRequisicion[] = $idRequisicion;
            foreach ($r['items'] as [$idx, $cantidad]) {
                DB::table('tr_requisiciones_refacciones')->insert([
                    'id_requisicion' => $idRequisicion,
                    'id_refaccion' => $idsRefaccion[$idx],
                    'n_cantidad_sugerida' => $cantidad,
                    'n_cantidad_solicitada' => $cantidad,
                    'n_costo_unitario' => $refacciones[$idx][4],
                    'id_motivo_pedido' => 2,
                    'id_prioridad' => 3,
                    'id_estatus_requisicion' => $r['id_estatus_requisicion'],
                    'b_activo' => 1,
                    'created_at' => $now->copy()->subDays(3),
                    'updated_at' => $now->copy()->subDays(3),
                ]);
            }
        }

        // ── Orden de compra sobre la requisición autorizada ────────────
        $idOrdenCompra = DB::table('tw_ordenes_compras')->insertGetId([
            's_folio_interno' => 'OC1R' . $idsRequisicion[1] . 'P2',
            's_observacion' => 'Orden de compra de demostración',
            'd_fecha_orden' => $hoy,
            'd_fecha_recepcion_estimada' => $now->copy()->addDays(5)->toDateString(),
            'n_total_estimado' => round($refacciones[1][4] * 12, 2),
            'id_proveedor' => 2,
            'id_requisicion' => $idsRequisicion[1],
            'id_estatus_orden_compra' => 1,
            'id_usuario_crea' => $adminId,
            'b_activo' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $idReqRef = DB::table('tr_requisiciones_refacciones')
            ->where('id_requisicion', $idsRequisicion[1])
            ->value('id_requisicion_refaccion');
        DB::table('tr_ordenes_compras_requisiciones_refacciones')->insert([
            'id_orden_compra' => $idOrdenCompra,
            'id_requisicion_refaccion' => $idReqRef,
            'n_cantidad_recibida' => 0,
            'b_activo' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ── Embarques (1 pendiente, 1 aprobado) ────────────────────────
        foreach ([[1, 1, [[0, 10]]], [2, 2, [[4, 30], [9, 10]]]] as [$idProveedor, $estatus, $entradas]) {
            $fechaEmbarque = $now->copy()->subDays($estatus === 2 ? 5 : 1);
            $idEmbarque = DB::table('tw_embarques')->insertGetId([
                'id_proveedor' => $idProveedor,
                'd_fecha_creacion' => $fechaEmbarque,
                'id_usuario_crea' => $adminId,
                'id_estatus_embarque' => $estatus,
                'b_activo' => 1,
            ]);
            foreach ($entradas as [$idx, $cantidad]) {
                DB::table('tr_entradas_embarque')->insert([
                    'id_embarque' => $idEmbarque,
                    'id_refaccion' => $idsRefaccion[$idx],
                    'id_estatus_entrada' => $estatus === 2 ? 2 : 1,
                    'n_cantidad' => $cantidad,
                    'n_precio_compra' => $refacciones[$idx][4],
                    's_codigo_barras' => sprintf('CB-DEMO-%04d', $idx + 1),
                    'd_fecha_creacion' => $fechaEmbarque,
                    'b_activo' => 1,
                ]);
            }
        }

        // ── Gastos ─────────────────────────────────────────────────────
        $gastos = [
            ['id_tipo_gasto' => 4, 'n_costo' => 350.00, 's_concepto' => 'Comida equipo reparto'],
            ['id_tipo_gasto' => 6, 'n_costo' => 890.00, 's_concepto' => 'Juego de dados y matracas'],
            ['id_tipo_gasto' => 2, 'n_costo' => 1200.00, 's_concepto' => 'Servicio de mensajería'],
            ['id_tipo_gasto' => 5, 'n_costo' => 240.00, 's_concepto' => 'Tornillería general'],
        ];
        foreach ($gastos as $i => $g) {
            DB::table('tw_gastos')->insert($g + [
                'id_sucursal' => 1,
                'n_cantidad' => 1,
                'd_fecha_gasto' => $now->copy()->subDays($i)->toDateString(),
                'd_fecha_creacion' => $now->copy()->subDays($i),
                'id_usuario_crea' => $adminId,
                'b_movil' => 0,
                'b_activo' => 1,
            ]);
        }

        // ── Repartos: destinos y órdenes ───────────────────────────────
        $idRepartidor = DB::table('tw_empleados')->where('s_nombre', 'Pedro')->value('id_empleado');
        $destinos = [
            ['s_nombre_destino' => 'Taller García Hnos', 's_direccion' => 'Av. Universidad 1201, San Nicolás', 's_referencia_destino' => 'Portón azul', 'id_tipo_destino' => 1],
            ['s_nombre_destino' => 'Servicio Automotriz Alfa', 's_direccion' => 'Calz. Madero 455, Centro', 's_referencia_destino' => 'Frente a la plaza', 'id_tipo_destino' => 1],
        ];
        $ordenes = [
            ['id_estatus_orden' => 4, 'id_repartidor' => null],            // pendiente de asignar
            ['id_estatus_orden' => 2, 'id_repartidor' => $idRepartidor],   // en reparto
        ];
        foreach ($destinos as $i => $d) {
            $idDestino = DB::table('tw_destinos')->insertGetId($d + ['b_activo' => 1]);
            DB::table('tw_ordenes')->insert([
                'id_destino' => $idDestino,
                's_nota_refaccionista' => 'Entregar en recepción',
                'id_repartidor' => $ordenes[$i]['id_repartidor'],
                'd_fecha_asignacion' => $ordenes[$i]['id_repartidor'] ? $now : null,
                'id_estatus_orden' => $ordenes[$i]['id_estatus_orden'],
                'd_inicio_reparto' => $ordenes[$i]['id_estatus_orden'] === 2 ? $now->copy()->subHours(2) : null,
                'b_activo' => 1,
            ]);
        }
    }
}
