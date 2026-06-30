<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  pedido: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
  cuotas: [],
  monto: 0,
  tipo_pago: '',
  estado_pago: 'pagado',
  observacion: '',
});

const tienePlan = computed(() => !!props.pedido?.plan_pago);
const cuotasPendientes = computed(() => props.pedido?.plan_pago?.cuotas?.filter(c => c.estado_cuota !== 'pagado') || []);

watch(() => props.pedido, (pedido) => {
  if (!pedido) return;
  form.reset();
  form.clearErrors();
  form.cuotas = [];
  form.monto = Number(pedido.total || 0);
  form.tipo_pago = '';
  form.estado_pago = 'pagado';
  form.observacion = '';
}, { immediate: true });

function formatMoney(value) {
  return new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(Number(value || 0));
}

function submit() {
  if (!props.pedido) return;

  if (tienePlan.value) {
    form.post(route('planes.pedido.pagarCuota', props.pedido.id), {
      preserveScroll: true,
      onSuccess: () => emit('close'),
    });
    return;
  }

  if (form.tipo_pago === 'qr') {
    form.get(route('planes.pedido.pagarQR', props.pedido.id), {
      preserveScroll: true,
      onSuccess: () => emit('close'),
    });
    return;
  }

  form.post(route('pagos.store', props.pedido.id), {
    preserveScroll: true,
    onSuccess: () => emit('close'),
  });
}
</script>

<template>
  <div v-if="show && pedido" class="modal-mask">
    <div class="modal-container">
      <div class="modal-header bg-success text-white p-3 rounded-top d-flex justify-content-between align-items-center">
        <h5 class="modal-title m-0">Registrar pago – Pedido #{{ pedido.id }}</h5>
        <button type="button" class="btn-close btn-close-white" @click="$emit('close')"></button>
      </div>

      <form @submit.prevent="submit">
        <div class="modal-body p-4 overflow-auto" style="max-height: 72vh;">
          <h6 class="fw-bold">Detalle del pedido:</h6>
          <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm mb-0">
              <thead class="table-light">
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>P/U</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="detalle in pedido.detalles || []" :key="detalle.id">
                  <td>{{ detalle.producto?.nombre || ('Producto #' + detalle.id_producto) }}</td>
                  <td>{{ detalle.cantidad }}</td>
                  <td>{{ formatMoney(detalle.precio_venta) }}</td>
                  <td>{{ formatMoney(detalle.subtotal) }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total real:</th>
                  <th>{{ formatMoney(pedido.total) }}</th>
                </tr>
              </tfoot>
            </table>
          </div>

          <div v-if="tienePlan">
            <h5 class="mt-3 border-bottom pb-2">Cuotas del plan de pago</h5>
            <div class="alert alert-info py-2">
              Seleccione una cuota pendiente. Se generará un QR de prueba por centavos y el monto real aparecerá en el detalle del QR.
            </div>

            <table class="table table-bordered mt-2">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Monto real</th>
                  <th>Vencimiento</th>
                  <th>Estado</th>
                  <th class="text-center">Pagar por QR</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="cuota in pedido.plan_pago.cuotas" :key="cuota.id">
                  <td>{{ cuota.numero_cuota }}</td>
                  <td>{{ formatMoney(cuota.monto) }}</td>
                  <td>{{ cuota.fecha_vencimiento }}</td>
                  <td>
                    <span v-if="cuota.estado_cuota === 'pagado'" class="badge bg-success">Pagado</span>
                    <span v-else class="badge bg-warning text-dark">{{ cuota.estado_cuota }}</span>
                  </td>
                  <td class="text-center">
                    <input v-if="cuota.estado_cuota !== 'pagado'" type="checkbox" :value="cuota.id" v-model="form.cuotas" class="form-check-input">
                  </td>
                </tr>
                <tr v-if="!pedido.plan_pago.cuotas?.length">
                  <td colspan="5" class="text-center text-muted">No hay cuotas registradas.</td>
                </tr>
              </tbody>
            </table>
            <div v-if="form.errors.cuotas" class="text-danger small">{{ form.errors.cuotas }}</div>
            <div v-if="cuotasPendientes.length === 0" class="alert alert-success py-2">Todas las cuotas están pagadas.</div>
          </div>

          <div v-else>
            <div class="mb-3">
              <label class="form-label">Monto real a pagar</label>
              <input type="number" class="form-control" v-model="form.monto" step="0.01" min="0.01" readonly>
              <small class="text-muted">Para QR de prueba se cobrará un monto bajo; el total real irá en el motivo/detalle del QR.</small>
              <div v-if="form.errors.monto" class="text-danger small">{{ form.errors.monto }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Tipo de pago</label>
              <select class="form-select" v-model="form.tipo_pago" required>
                <option value="" disabled>Seleccione...</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="qr">QR PagoFácil</option>
              </select>
              <div v-if="form.errors.tipo_pago" class="text-danger small">{{ form.errors.tipo_pago }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Observación</label>
              <textarea class="form-control" rows="2" v-model="form.observacion"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light rounded-bottom">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button type="submit" class="btn btn-success" :disabled="form.processing || (tienePlan && cuotasPendientes.length === 0)">
            <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
            {{ tienePlan ? 'Generar QR de cuota' : 'Registrar pago' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.modal-mask { position: fixed; z-index: 9999; background: rgba(0,0,0,.5); top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
.modal-container { background: white; width: min(850px, 96%); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,.3); display: flex; flex-direction: column; }
.modal-header .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
</style>
