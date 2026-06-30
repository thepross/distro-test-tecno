<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

const props = defineProps({
    show: Boolean,
    venta: Object,
});

const emit = defineEmits(["close"]);

const previewCuotas = ref([]);

const form = useForm({
    cantidad_cuotas: 1,
    total_deuda: props.venta?.total || 0,
    fecha_inicio: new Date().toISOString().split('T')[0],
    estado: 'pendiente',
    cuotas: [],
});

watch(() => props.venta, (newVenta) => {
    if (newVenta) {
        form.total_deuda = newVenta.total || 0;
        form.cantidad_cuotas = 1;
        previewCuotas.value = [];
    }
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};

const generarCuotas = () => {
    if (!form.cantidad_cuotas || form.cantidad_cuotas < 1) return;

    previewCuotas.value = [];
    const montoPorCuota = form.total_deuda / form.cantidad_cuotas;
    let fechaBase = new Date(form.fecha_inicio + 'T00:00:00');

    for (let i = 0; i < form.cantidad_cuotas; i++) {
        let fechaCuota = new Date(fechaBase);
        fechaCuota.setMonth(fechaBase.getMonth() + i);

        previewCuotas.value.push({
            numero: i + 1,
            monto: montoPorCuota,
            fecha: fechaCuota.toISOString().split('T')[0]
        });
    }
};

const submit = () => {
    form.cuotas = previewCuotas.value.map(cuota => ({
        monto: cuota.monto,
        fecha: cuota.fecha
    }));
    console.log(form);
    form.post(route('planes.store', props.venta.id), {
        onSuccess: () => {
            emit("close");
            previewCuotas.value = [];
        }
    });
};
</script>

<template>
    <div v-if="show && venta" class="modal-mask">
        <div class="modal-container">

            <!-- HEADER -->
            <div
                class="modal-header bg-warning text-dark p-3 rounded-top d-flex justify-content-between align-items-center">
                <h5 class="modal-title m-0 fw-bold">Crear Plan de Pago - Venta #{{ venta.id }}</h5>
                <button type="button" class="btn-close" @click="$emit('close')"></button>
            </div>

            <form @submit.prevent="submit">
                <div class="modal-body p-4">

                    <p class="fs-5">
                        <b>Total venta:</b> <span class="text-success">{{ formatCurrency(venta.total) }}</span>
                    </p>
                    <hr>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Cantidad de cuotas</label>
                            <input type="number" class="form-control" v-model="form.cantidad_cuotas" min="1" required>
                            <div v-if="form.errors.cantidad_cuotas" class="text-danger small">{{
                                form.errors.cantidad_cuotas }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Total deuda</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control bg-light"
                                    v-model="form.total_deuda" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" @click="generarCuotas">
                                <i class="fas fa-calculator me-2"></i> Generar cuotas
                            </button>
                        </div>
                    </div>

                    <!-- TABLA PREVISUALIZACIÓN -->
                    <div class="mt-4">
                        <label class="form-label fw-bold text-muted">Previsualización del plan:</label>
                        <div class="table-responsive border rounded" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-striped table-sm mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th># Cuota</th>
                                        <th>Monto Estimado</th>
                                        <th>Fecha Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cuota in previewCuotas" :key="cuota.numero">
                                        <td>{{ cuota.numero }}</td>
                                        <td>{{ formatCurrency(cuota.monto) }}</td>
                                        <td>{{ cuota.fecha }}</td>
                                    </tr>
                                    <tr v-if="previewCuotas.length === 0">
                                        <td colspan="3" class="text-center text-muted py-3">
                                            Presiona "Generar cuotas" para ver el detalle.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3 g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha inicio (Primera cuota)</label>
                            <input type="date" class="form-control" v-model="form.fecha_inicio" required
                                @change="generarCuotas">
                            <!-- @change regenera las fechas si el usuario cambia el inicio después de generar -->
                            <div v-if="form.errors.fecha_inicio" class="text-danger small">{{ form.errors.fecha_inicio
                                }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado Inicial</label>
                            <select class="form-select" v-model="form.estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_curso">En curso</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                            <div v-if="form.errors.estado" class="text-danger small">{{ form.errors.estado }}</div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold" :disabled="form.processing">
                        <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
                        Crear Plan de Pago
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
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>