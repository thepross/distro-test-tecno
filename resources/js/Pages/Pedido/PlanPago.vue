<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
  show: Boolean,
  pedido: Object,
});

const emit = defineEmits(['close']);

const hoy = () => new Date().toISOString().slice(0, 10);

const form = useForm({
  cantidad_cuotas: 2,
  total_deuda: 0,
  fecha_inicio: hoy(),
  estado_plan: 'pendiente',
  cuotas: [],
});

function formatMoney(value) {
  return new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(Number(value || 0));
}

function fechaMasMeses(fecha, meses) {
  const base = new Date(`${fecha}T00:00:00`);
  base.setMonth(base.getMonth() + meses);
  return base.toISOString().slice(0, 10);
}

function generarCuotas() {
  const cantidad = Math.max(1, Number(form.cantidad_cuotas || 1));
  const total = Number(form.total_deuda || 0);
  const montoBase = Math.floor((total / cantidad) * 100) / 100;
  const cuotas = [];
  let acumulado = 0;

  for (let i = 0; i < cantidad; i++) {
    let monto = i === cantidad - 1 ? Number((total - acumulado).toFixed(2)) : montoBase;
    acumulado = Number((acumulado + monto).toFixed(2));
    cuotas.push({
      numero_cuota: i + 1,
      monto,
      fecha_vencimiento: fechaMasMeses(form.fecha_inicio, i),
    });
  }

  form.cuotas = cuotas;
}

watch(() => props.pedido, (pedido) => {
  if (!pedido) return;
  form.reset();
  form.clearErrors();
  form.cantidad_cuotas = 2;
  form.total_deuda = Number(pedido.total || 0);
  form.fecha_inicio = hoy();
  form.estado_plan = 'pendiente';
  generarCuotas();
}, { immediate: true });

watch(() => [form.cantidad_cuotas, form.total_deuda, form.fecha_inicio], generarCuotas);

function submit() {
  if (!props.pedido) return;
  form.post(route('planes.pedido.store', props.pedido.id), {
    preserveScroll: true,
    onSuccess: () => emit('close'),
  });
}
</script>

<template>
  <div v-if="show && pedido" class="modal-mask">
    <div class="modal-container">
      <div class="modal-header bg-primary text-white p-3 rounded-top d-flex justify-content-between align-items-center">
        <h5 class="modal-title m-0">Plan de pago – Pedido #{{ pedido.id }}</h5>
        <button type="button" class="btn-close btn-close-white" @click="$emit('close')"></button>
      </div>

      <form @submit.prevent="submit">
        <div class="modal-body p-4 overflow-auto" style="max-height: 72vh;">
          <div v-if="pedido.plan_pago" class="alert alert-warning">
            Este pedido ya tiene un plan de pago activo.
          </div>

          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label fw-bold">Cantidad de cuotas</label>
              <input type="number" min="1" class="form-control" v-model="form.cantidad_cuotas" required>
              <div v-if="form.errors.cantidad_cuotas" class="text-danger small">{{ form.errors.cantidad_cuotas }}</div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold">Total deuda</label>
              <input type="number" min="0.01" step="0.01" class="form-control" v-model="form.total_deuda" required>
              <small class="text-muted">Total del pedido: {{ formatMoney(pedido.total) }}</small>
              <div v-if="form.errors.total_deuda" class="text-danger small">{{ form.errors.total_deuda }}</div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold">Fecha inicio</label>
              <input type="date" class="form-control" v-model="form.fecha_inicio" required>
              <div v-if="form.errors.fecha_inicio" class="text-danger small">{{ form.errors.fecha_inicio }}</div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold">Estado</label>
              <select class="form-select" v-model="form.estado_plan" required>
                <option value="pendiente">Pendiente</option>
                <option value="en curso">En curso</option>
              </select>
            </div>
          </div>

          <h6 class="fw-bold">Previsualización de cuotas</h6>
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Monto</th>
                  <th>Fecha vencimiento</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="cuota in form.cuotas" :key="cuota.numero_cuota">
                  <td>{{ cuota.numero_cuota }}</td>
                  <td>
                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm" v-model="cuota.monto">
                  </td>
                  <td>
                    <input type="date" class="form-control form-control-sm" v-model="cuota.fecha_vencimiento">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="form.errors.plan" class="text-danger small">{{ form.errors.plan }}</div>
          <div v-if="form.errors.cuotas" class="text-danger small">{{ form.errors.cuotas }}</div>
        </div>

        <div class="modal-footer bg-light rounded-bottom">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button type="submit" class="btn btn-primary" :disabled="form.processing || !!pedido.plan_pago">
            <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
            Crear plan de pago
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.modal-mask { position: fixed; z-index: 9999; background: rgba(0,0,0,.5); top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
.modal-container { background: white; width: min(780px, 96%); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,.3); display: flex; flex-direction: column; }
.modal-header .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
</style>
