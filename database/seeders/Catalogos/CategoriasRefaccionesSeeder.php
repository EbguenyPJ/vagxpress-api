<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_categorias_refacciones` (15 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class CategoriasRefaccionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_categorias_refacciones')->truncate();

        $rows = [
            ['id_categoria_refaccion' => 1, 's_categoria_refaccion' => 'Motor', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/71/ff/83/71ff835c76699d3054422510dc246972.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 2, 's_categoria_refaccion' => 'Sistema de Ignición', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/62/07/e0/6207e09c584959112082962a4f2f8e2c.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 3, 's_categoria_refaccion' => 'Filtros', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/cf/b0/db/cfb0db90b9289face2753705713af29e.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 4, 's_categoria_refaccion' => 'Sistema de Frenos', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/1c/a1/04/1ca10460fe15cf9c8922631712465289.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 5, 's_categoria_refaccion' => 'Suspensión y Dirección', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/db/b5/0e/dbb50e9bb0fa86222c088bca7255951a.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 6, 's_categoria_refaccion' => 'Transmisión y Embrague', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/5d/e1/61/5de161953256ac357fc6f37f54836cbd.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 7, 's_categoria_refaccion' => 'Sistema de Enfriamiento', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/c9/cb/21/c9cb2137f983ec704d45fa848a49f6bb.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 8, 's_categoria_refaccion' => 'Sistema Eléctrico', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/92/1d/63/921d637a9d0a204baf14829fc6f6bccb.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 9, 's_categoria_refaccion' => 'Sistema de Escape', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/73/a6/7d/73a67de91a40d0c5f4649258b79a5ba6.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 10, 's_categoria_refaccion' => 'Iluminación', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/56/3b/a4/563ba4dfa30233a6e424438ebe028c55.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 11, 's_categoria_refaccion' => 'Carrocería y Colisión', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/e4/48/ce/e448cebbe4c26dda1336ef4b3ddaac3b.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 12, 's_categoria_refaccion' => 'Sensores y Módulos', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/74/d2/37/74d237212eeef0707019f45316c6777e.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 13, 's_categoria_refaccion' => 'Químicos y Fluidos', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/a5/49/ce/a549ce11e686ae9e5c6837db0e0e0e96.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 14, 's_categoria_refaccion' => 'Aire Acondicionado', 's_img_categoria_refaccion' => 'https://i.pinimg.com/1200x/09/1c/34/091c340395215d99ad7d46a98352e61d.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_refaccion' => 15, 's_categoria_refaccion' => 'Llantas y Rines', 's_img_categoria_refaccion' => 'https://i.pinimg.com/736x/d2/d5/36/d2d536c084e9080070d49c7aba263402.jpg', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_categorias_refacciones')->insert($chunk);
        }
    }
}
