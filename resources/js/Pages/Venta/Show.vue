<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: Boolean,
    venta: Object,
});

const emit = defineEmits(["close"]);

// Formatear moneda
const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};

const infoPago = computed(() => {
    if (!props.venta) return { pagada: false, totalAbonado: 0 };

    const totalPagos = props.venta.pagos?.reduce((sum, p) => sum + parseFloat(p.monto), 0) || 0;
    const totalCuotas = props.venta.plan?.cuotas
        ?.filter(c => c.estado === 'pagado')
        .reduce((sum, c) => sum + parseFloat(c.monto), 0) || 0;

    const totalAbonado = totalPagos + totalCuotas;

    return {
        pagada: totalAbonado >= props.venta.total,
        totalAbonado: totalAbonado
    };
});
</script>

<template>
    <div v-if="show && venta" class="modal-mask">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        Detalles de Venta #{{ venta.id }}
                        <span v-if="infoPago.pagada" class="badge bg-success ms-2">Pagado Completo</span>
                        <span v-else class="badge bg-warning text-dark ms-2">Pendiente</span>
                    </h5>
                    <button type="button" class="btn-close" @click="$emit('close')"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Cliente:</strong> {{ venta.cliente?.nombre || 'No asignado' }}</p>
                            <p><strong>Vendedor:</strong> {{ venta.usuario?.nombre || 'No asignado' }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p><strong>Fecha:</strong> {{ venta.fecha_venta }}</p>
                            <p><strong>Total Venta:</strong> <span class="fs-5 fw-bold">{{ formatCurrency(venta.total)
                            }}</span></p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Productos / Servicios</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="detalle in venta.detalles" :key="detalle.id">
                                    <td>
                                        {{ detalle.producto?.nombre || detalle.servicio?.descripcion || 'Item eliminado'
                                        }}
                                    </td>
                                    <td class="text-center">{{ detalle.cantidad }}</td>
                                    <td class="text-end">{{ formatCurrency(detalle.precio) }}</td>
                                    <td class="text-end fw-bold">{{ formatCurrency(detalle.precio * detalle.cantidad) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL:</th>
                                    <th class="text-end">{{ formatCurrency(venta.total) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 1055;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 30px;
    transition: opacity 0.3s ease;
}

.modal-dialog {
    width: 100%;
    margin: 0 20px;
}

/* Animación de entrada */
.modal-content {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>