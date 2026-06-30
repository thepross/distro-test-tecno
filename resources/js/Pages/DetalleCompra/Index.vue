<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ detalle_compras: Array, compras: Array, productos: Array });
const columns = [
  { key: 'compra.id', label: 'COMPRA' }, { key: 'producto.nombre', label: 'PRODUCTO' }, { key: 'cantidad', label: 'CANTIDAD' },
  { key: 'precio_compra', label: 'P. COMPRA', format: 'money' }, { key: 'subtotal', label: 'SUBTOTAL', format: 'money' }
];
const fields = [
  { key: 'id_compra', label: 'Compra', type: 'select', optionsKey: 'compras', optionLabel: 'id' },
  { key: 'id_producto', label: 'Producto', type: 'select', optionsKey: 'productos', optionLabel: 'nombre' },
  { key: 'cantidad', label: 'Cantidad', type: 'number', default: 1 },
  { key: 'precio_compra', label: 'Precio de compra', type: 'number', default: 0 }
];
</script>
<template><CrudIndex title="Gestión de detalle de compras" funcionalidad="DetalleCompra" route-name="detalle_compra" :items="detalle_compras" :columns="columns" :fields="fields" :options="{ compras, productos }" item-label="detalle de compra" /></template>
