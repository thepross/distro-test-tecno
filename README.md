# Distribuidora Carla

Proyecto Laravel 11 adaptado desde la estructura administrativa existente para una distribuidora de productos.

## Módulos incluidos

- CU1 Gestión de roles
- CU2 Gestión de usuarios
- CU3 Gestión de productos
- CU4 Gestión de compras
- CU5 Gestión de detalle de compras
- CU6 Gestión de inventario
- CU7 Gestión de pedidos
- CU8 Gestión de detalle de pedidos
- CU9 Gestión de entregas
- CU10 Gestión de pagos
- CU11 Gestión de plan de pago
- CU12 Gestión de cuotas
- CU13 Reportes

Se mantiene el paquete administrativo de roles, usuarios, privilegios y configuración.

## Instalación básica

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run dev
php artisan serve
```

## Usuario inicial

- Correo: `admin@carla.com`
- Contraseña: `secret`

## Base de datos

Las migraciones fueron ajustadas al modelo de Distribuidora Carla: productos, compras, detalle_compra, inventario, pedidos, detalle_pedido, entregas, pagos, plan_pago y cuotas.
