<script setup>
import CrudIndex from '@/Components/CrudIndex.vue';
const props = defineProps({ cuotas: Array, planes: Array });
const columns = [
  { key: 'plan_pago.id', label: 'PLAN' }, { key: 'numero_cuota', label: 'N° CUOTA' }, { key: 'fecha_vencimiento', label: 'VENCIMIENTO', format: 'date' },
  { key: 'monto', label: 'MONTO', format: 'money' }, { key: 'estado_cuota', label: 'ESTADO' }, { key: 'fecha_pago', label: 'FECHA PAGO', format: 'date' }
];
const fields = [
  { key: 'id_plan_pago', label: 'Plan de pago', type: 'select', optionsKey: 'planes', optionLabel: 'id' },
  { key: 'numero_cuota', label: 'Número de cuota', type: 'number', default: 1 },
  { key: 'fecha_vencimiento', label: 'Fecha de vencimiento', type: 'date' },
  { key: 'monto', label: 'Monto', type: 'number', default: 0 },
  { key: 'estado_cuota', label: 'Estado de cuota', default: 'pendiente' },
  { key: 'fecha_pago', label: 'Fecha de pago', type: 'date' }
];
</script>
<template><CrudIndex title="Gestión de cuotas" funcionalidad="Cuota" route-name="cuota" :items="cuotas" :columns="columns" :fields="fields" :options="{ planes }" item-label="cuota" /></template>
