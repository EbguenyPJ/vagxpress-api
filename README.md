# VagXpress — API

API backend de **VagXpress**, un sistema **POS (punto de venta) para refaccionarías**. Construida con **Laravel 10** y autenticación vía **Laravel Sanctum**.

> Frontend: **vagxpress-web** (Angular 19) — carpeta `../RefaccionesFront`.

## Tecnologías

- **PHP** 8.1+
- **Laravel** 10
- **Laravel Sanctum** 3.3 (autenticación por tokens)
- **barryvdh/laravel-dompdf** (generación de PDF)
- **MySQL**

## Funcionalidades

- **Ventas y cortes** — registro de ventas de refacciones y cortes de caja.
- **Clientes** — gestión de clientes, **crédito** y **abonos**.
- **Compras** — requisiciones, órdenes de compra y proveedores.
- **Refacciones** — catálogo, cotizaciones y equivalencias.
- **Embarques**, **gastos** y **alertas** (p. ej. cambio de proveedor por mejor precio).
- **Dashboard**, envío de **correos** y endpoints **móviles**.
- Módulos de **administración** y **catálogos** (`Admin/`, `Catalogos/`, `Movil/`).

## Requisitos

- PHP 8.1+ y Composer
- MySQL

## Instalación y ejecución

```bash
composer install
cp .env.example .env        # configura conexión MySQL y APP_URL
php artisan key:generate
php artisan migrate          # agrega --seed si hay seeders
php artisan serve            # http://localhost:8000
```

> Base de datos por defecto: `savecarc_refaccionaria_dev` (ajústala en `.env`).

## Estructura principal

- `app/Http/Controllers` — controladores REST (ventas, cortes, requisiciones, órdenes de compra, refacciones, clientes, proveedores, etc.).
- `app/Models` — modelos Eloquent.
- `routes/` — definición de endpoints de la API.
