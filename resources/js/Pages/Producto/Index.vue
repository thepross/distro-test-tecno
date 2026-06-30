<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ productos: Array });
const columns = [
  { key: 'nombre', label: 'NOMBRE' }, { key: 'categoria', label: 'CATEGORÍA' }, { key: 'marca', label: 'MARCA' },
  { key: 'unidad_medida', label: 'UNIDAD' }, { key: 'precio_compra', label: 'P. COMPRA', format: 'money' },
  { key: 'precio_venta', label: 'P. VENTA', format: 'money' }, { key: 'stock_minimo', label: 'STOCK MÍNIMO' }, { key: 'codigo_qr', label: 'CÓDIGO QR' }
];
const fields = [
  { key: 'nombre', label: 'Nombre' }, { key: 'descripcion', label: 'Descripción', type: 'textarea' }, { key: 'categoria', label: 'Categoría' },
  { key: 'marca', label: 'Marca' }, { key: 'unidad_medida', label: 'Unidad de medida' },
  { key: 'precio_compra', label: 'Precio de compra', type: 'number' }, { key: 'precio_venta', label: 'Precio de venta', type: 'number' },
  { key: 'codigo_qr', label: 'Código QR' }, { key: 'stock_minimo', label: 'Stock mínimo', type: 'number' }
];
</script>
<template><CrudIndex title="Gestión de productos" funcionalidad="Producto" route-name="producto" :items="productos" :columns="columns" :fields="fields" item-label="producto" /></template>
