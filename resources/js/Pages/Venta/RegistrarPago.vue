<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    venta: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    cuotas: [],
    monto: 0,
    tipo_pago: "",
});

watch(() => props.venta, (newVenta) => {
    if (newVenta) {
        form.reset();
        form.cuotas = [];
        form.monto = newVenta.total;
        form.tipo_pago = "";
    }
}, { immediate: true });

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};

const submit = () => {
    if (props.venta.planes?.length > 0) {
        form.post(route('planes.pagarCuota', props.venta.id), {
            onSuccess: () => emit("close"),
        });
        return;
    }

    if (form.tipo_pago === "qr") {
        console.log("Pago con QR seleccionado");
        form.get(route('planes.pagarQR', props.venta.id), {
            onSuccess: () => emit("close"),
        });
        return;
    }

    form.post(route('pagos.store', props.venta.id), {
        onSuccess: () => emit("close"),
    });

};
</script>

<template>
    <div v-if="show && venta" class="modal-mask">
        <div class="modal-container">

            <!-- HEADER -->
            <div
                class="modal-header bg-success text-white p-3 rounded-top d-flex justify-content-between align-items-center">
                <h5 class="modal-title m-0">Registrar Pago – Venta #{{ venta.id }}</h5>
                <button type="button" class="btn-close btn-close-white" @click="$emit('close')"></button>
            </div>

            <form @submit.prevent="submit">
                <div class="modal-body p-4 overflow-auto" style="max-height: 70vh;">

                    <!-- DETALLE DE LA VENTA -->
                    <h6 class="fw-bold">Detalle de la Venta:</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Cantidad</th>
                                    <th>P/U</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="detalle in venta.detalles" :key="detalle.id">
                                    <td>{{ detalle.producto?.nombre || 'Producto' }}</td>
                                    <td>{{ detalle.cantidad }}</td>
                                    <td>{{ formatCurrency(detalle.precio) }}</td>
                                    <td>{{ formatCurrency(detalle.cantidad * detalle.precio) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>{{ formatCurrency(venta.total) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- CASO 1: TIENE PLAN DE PAGO -->
                    <div v-if="props.venta.planes?.length > 0">
                        <h5 class="mt-3 border-bottom pb-2">Cuotas del plan de pago</h5>

                        <table class="table table-bordered mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Monto</th>
                                    <th>Vencimiento</th>
                                    <th>Estado</th>
                                    <th class="text-center">Pagar</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ console.log(venta.planes) }}
                                <tr v-for="(cuota, index) in venta.planes[0].cuotas" :key="cuota.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ formatCurrency(cuota.monto) }}</td>
                                    <td>{{ cuota.fecha_vencimiento }}</td>
                                    <td>
                                        <span v-if="cuota.estado === 'pagado'" class="badge bg-success">Pagado</span>
                                        <span v-else class="badge bg-warning text-dark">{{ cuota.estado }}</span>
                                    </td>
                                    <td class="text-center">
                                        <input v-if="cuota.estado === 'pendiente'" type="checkbox" :value="cuota.id"
                                            v-model="form.cuotas" class="form-check-input">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-info py-2">
                            <small><i class="fas fa-info-circle"></i> Seleccione las cuotas que desea marcar como
                                pagadas.</small>
                        </div>
                        <div v-if="form.errors.cuotas" class="text-danger small">{{ form.errors.cuotas }}</div>
                    </div>

                    <!-- CASO 2: PAGO INDEPENDIENTE (NO TIENE PLAN) -->
                    <div v-else>
                        <div class="mb-3">
                            <label class="form-label">Monto a pagar</label>
                            <input type="number" class="form-control" v-model="form.monto" step="0.01" min="0.01"
                                :max="venta.total" readonly>
                            <small class="text-muted">Total de la venta: {{ formatCurrency(venta.total) }}</small>
                            <div v-if="form.errors.monto" class="text-danger small">{{ form.errors.monto }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de pago</label>
                            <select class="form-select" v-model="form.tipo_pago" required>
                                <option value="" disabled>Seleccione...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="yape">Yape</option>
                                <option value="qr">QR</option>
                            </select>
                            <div v-if="form.errors.tipo_pago" class="text-danger small">{{ form.errors.tipo_pago }}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
                    <button type="submit" class="btn btn-success" :disabled="form.processing">
                        <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
                        Registrar Pago
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.5);
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
}

.modal-container {
    background: white;
    width: min(800px, 95%);
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.3s ease;
    display: flex;
    flex-direction: column;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>