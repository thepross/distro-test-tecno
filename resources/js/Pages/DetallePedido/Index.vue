<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ detalle_pedidos: Array, pedidos: Array, productos: Array });
const columns = [
  { key: 'pedido.id', label: 'PEDIDO' }, { key: 'producto.nombre', label: 'PRODUCTO' }, { key: 'cantidad', label: 'CANTIDAD' },
  { key: 'precio_venta', label: 'P. VENTA', format: 'money' }, { key: 'subtotal', label: 'SUBTOTAL', format: 'money' }
];
const fields = [
  { key: 'id_pedido', label: 'Pedido', type: 'select', optionsKey: 'pedidos', optionLabel: 'id' },
  { key: 'id_producto', label: 'Producto', type: 'select', optionsKey: 'productos', optionLabel: 'nombre' },
  { key: 'cantidad', label: 'Cantidad', type: 'number', default: 1 },
  { key: 'precio_venta', label: 'Precio de venta', type: 'number', default: 0 }
];
</script>
<template><CrudIndex title="Gestión de detalle de pedidos" funcionalidad="DetallePedido" route-name="detalle_pedido" :items="detalle_pedidos" :columns="columns" :fields="fields" :options="{ pedidos, productos }" item-label="detalle de pedido" /></template>
