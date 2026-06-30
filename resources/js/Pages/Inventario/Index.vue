<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ inventarios: Array, productos: Array });
const columns = [
  { key: 'producto.nombre', label: 'PRODUCTO' }, { key: 'tipo_movimiento', label: 'TIPO' }, { key: 'cantidad', label: 'CANTIDAD' },
  { key: 'fecha_movimiento', label: 'FECHA', format: 'date' }, { key: 'stock_actual', label: 'STOCK ACTUAL' }, { key: 'descripcion', label: 'DESCRIPCIÓN' }
];
const fields = [
  { key: 'id_producto', label: 'Producto', type: 'select', optionsKey: 'productos', optionLabel: 'nombre' },
  { key: 'tipo_movimiento', label: 'Tipo de movimiento', default: 'entrada' },
  { key: 'cantidad', label: 'Cantidad', type: 'number', default: 0 },
  { key: 'fecha_movimiento', label: 'Fecha de movimiento', type: 'date' },
  { key: 'stock_actual', label: 'Stock actual', type: 'number', default: 0 },
  { key: 'descripcion', label: 'Descripción', type: 'textarea' }
];
</script>
<template><CrudIndex title="Gestión de inventario" funcionalidad="Inventario" route-name="inventario" :items="inventarios" :columns="columns" :fields="fields" :options="{ productos }" item-label="movimiento de inventario" /></template>
