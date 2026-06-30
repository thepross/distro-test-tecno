<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ entregas: Array, pedidos: Array, repartidores: Array });
const columns = [
  { key: 'pedido.id', label: 'PEDIDO' }, { key: 'repartidor.nombre', label: 'REPARTIDOR' }, { key: 'fecha_salida', label: 'SALIDA', format: 'date' },
  { key: 'fecha_entrega', label: 'ENTREGA', format: 'date' }, { key: 'direccion_entrega', label: 'DIRECCIÓN' }, { key: 'estado_entrega', label: 'ESTADO' }
];
const fields = [
  { key: 'id_pedido', label: 'Pedido', type: 'select', optionsKey: 'pedidos', optionLabel: 'id' },
  { key: 'id_repartidor', label: 'Repartidor', type: 'select', optionsKey: 'repartidores', optionLabel: 'nombre' },
  { key: 'fecha_salida', label: 'Fecha de salida', type: 'date' },
  { key: 'fecha_entrega', label: 'Fecha de entrega', type: 'date' },
  { key: 'direccion_entrega', label: 'Dirección de entrega' },
  { key: 'estado_entrega', label: 'Estado de entrega', default: 'pendiente' },
  { key: 'observacion', label: 'Observación', type: 'textarea' }
];
</script>
<template><CrudIndex title="Gestión de entregas" funcionalidad="Entrega" route-name="entrega" :items="entregas" :columns="columns" :fields="fields" :options="{ pedidos, repartidores }" item-label="entrega" /></template>
