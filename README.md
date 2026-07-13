# API Refaccionaria — VagXpress

Backend Laravel 10 del sistema de gestión de refaccionaria (POS, inventario,
compras, embarques, repartos y cortes de caja). Frontend en el repo
`RefaccionesFront` (Angular 19).

## Desarrollo local

Requisitos: PHP 8.1+, Composer, Docker.

```bash
composer install
cp .env.example .env && php artisan key:generate

# Base de datos local (MariaDB 10.6, el mismo motor que el hosting)
docker compose up -d

php artisan migrate:fresh --seed
php artisan serve            # http://127.0.0.1:8000
```

Usuario local: **admin / admin123** (solo entorno local; los seeders crean
además catálogos completos y datos de demostración).

## Tests

La suite corre contra una BD efímera en Docker (tmpfs, puerto 3308):

```bash
docker compose up -d mysql-testing
php artisan test
```

## Arquitectura

```
routes/api.php        Rutas agrupadas por dominio bajo auth:sanctum
app/Http/Controllers/Api   Controladores delgados (FormRequest → Service → Resource)
app/Http/Requests     Validación por endpoint
app/Http/Resources    Serialización de respuestas
app/Services          Lógica de negocio y transacciones
app/Models            Eloquent con relaciones, casts y constantes de dominio
app/Support/ApiResponse    Envoltura uniforme {status, message, data}
app/Exceptions/Handler     Errores JSON centralizados (401/404/422/500)
```

Convenciones de BD heredadas y conservadas: prefijos `tc_` (catálogo),
`tw_` (operativa), `tr_` (pivote); columnas `id_/s_/n_/b_/d_`.

## Despliegue a entornos existentes (dev/prod)

Las migraciones de este repo crean el esquema desde cero **con foreign keys
e índices**. Las BD de dev/prod existentes tienen los mismos nombres de
tablas/columnas pero sin constraints; antes de apuntar este código a esas
BD hay que ejecutar una migración incremental que añada las FKs e índices
(y depurar los datos huérfanos que impidan crearlas). Ese upgrade es un
trabajo aparte y consciente — no correr `migrate:fresh` contra dev/prod.

Nota de seguridad: las credenciales de BD y SMTP que estaban en el `.env`
histórico deben rotarse en el servidor.
