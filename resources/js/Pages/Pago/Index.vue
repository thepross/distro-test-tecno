<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ pagos: Array, pedidos: Array });
const columns = [
  { key: 'pedido.id', label: 'PEDIDO' }, { key: 'fecha_pago', label: 'FECHA', format: 'date' }, { key: 'monto', label: 'MONTO', format: 'money' },
  { key: 'tipo_pago', label: 'TIPO' }, { key: 'estado_pago', label: 'ESTADO' }, { key: 'observacion', label: 'OBSERVACIÓN' }
];
const fields = [
  { key: 'id_pedido', label: 'Pedido', type: 'select', optionsKey: 'pedidos', optionLabel: 'id' },
  { key: 'fecha_pago', label: 'Fecha de pago', type: 'date' },
  { key: 'monto', label: 'Monto', type: 'number', default: 0 },
  { key: 'tipo_pago', label: 'Tipo de pago', default: 'efectivo' },
  { key: 'estado_pago', label: 'Estado de pago', default: 'pendiente' },
  { key: 'observacion', label: 'Observación', type: 'textarea' }
];
</script>
<template><CrudIndex title="Gestión de pagos" funcionalidad="Pago" route-name="pago" :items="pagos" :columns="columns" :fields="fields" :options="{ pedidos }" item-label="pago" /></template>
