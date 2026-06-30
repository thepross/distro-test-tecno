<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ planes: Array, pedidos: Array });
const columns = [
  { key: 'pedido.id', label: 'PEDIDO' }, { key: 'cantidad_cuotas', label: 'CUOTAS' }, { key: 'monto_cuota', label: 'MONTO CUOTA', format: 'money' },
  { key: 'total_deuda', label: 'TOTAL DEUDA', format: 'money' }, { key: 'saldo_pendiente', label: 'SALDO', format: 'money' }, { key: 'estado_plan', label: 'ESTADO' }
];
const fields = [
  { key: 'id_pedido', label: 'Pedido', type: 'select', optionsKey: 'pedidos', optionLabel: 'id' },
  { key: 'cantidad_cuotas', label: 'Cantidad de cuotas', type: 'number', default: 1 },
  { key: 'monto_cuota', label: 'Monto de cuota', type: 'number', default: 0 },
  { key: 'total_deuda', label: 'Total deuda', type: 'number', default: 0 },
  { key: 'saldo_pendiente', label: 'Saldo pendiente', type: 'number', default: 0 },
  { key: 'fecha_inicio', label: 'Fecha de inicio', type: 'date' },
  { key: 'estado_plan', label: 'Estado del plan', default: 'pendiente' }
];
</script>
<template><CrudIndex title="Gestión de plan de pago" funcionalidad="PlanPago" route-name="planes" :items="planes" :columns="columns" :fields="fields" :options="{ pedidos }" item-label="plan de pago" /></template>
