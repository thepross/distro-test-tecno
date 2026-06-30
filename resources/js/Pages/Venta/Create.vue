<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

const props = defineProps({
    show: Boolean,
    clientes: Array,
    productos: Array,
    servicios: Array,
});

const emit = defineEmits(["close"]);

const productoSeleccionado = ref("");
const servicioSeleccionado = ref("");
const cantidad = ref(1);

const form = useForm({
    cliente_id: "",
    detalles: [],
});

watch(productoSeleccionado, (val) => {
    if (val) servicioSeleccionado.value = "";
});

watch(servicioSeleccionado, (val) => {
    if (val) productoSeleccionado.value = "";
});

const totalGeneral = computed(() => {
    return form.detalles.reduce((acc, item) => acc + item.subtotal, 0);
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};

const agregarDetalle = () => {
    if (!productoSeleccionado.value && !servicioSeleccionado.value) {
        alert("Seleccione un producto o servicio.");
        return;
    }
    if (cantidad.value < 1) {
        alert("La cantidad debe ser mayor a 0.");
        return;
    }

    let item = null;

    if (productoSeleccionado.value) {
        const precio = productoSeleccionado.value.precio;

        item = {
            tipo: "Producto",
            producto_id: productoSeleccionado.value.id,
            servicio_id: null,
            nombre: productoSeleccionado.value.nombre,
            cantidad: cantidad.value,
            precio: precio,
            subtotal: precio * cantidad.value
        };
    } else {
        const precio = servicioSeleccionado.value.precio;

        item = {
            tipo: "Servicio",
            producto_id: null,
            servicio_id: servicioSeleccionado.value.id,
            nombre: servicioSeleccionado.value.descripcion,
            cantidad: cantidad.value,
            precio: precio,
            subtotal: precio * cantidad.value
        };
    }
    form.detalles.push(item);
    productoSeleccionado.value = "";
    servicioSeleccionado.value = "";
    cantidad.value = 1;
};

const eliminarDetalle = (index) => {
    form.detalles.splice(index, 1);
};

const submit = () => {
    if (form.detalles.length === 0) {
        alert("Debes agregar al menos un detalle a la venta.");
        return;
    }

    form.post(route('venta.store'), {
        onSuccess: () => {
            form.reset();
            form.detalles = [];
            emit("close");
        }
    });
};
</script>

<template>
    <div v-if="show" class="modal-mask">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Agregar Venta</h5>
                    <button type="button" class="btn-close" @click="$emit('close')"></button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="submit">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Cliente</label>
                                <select v-model="form.cliente_id" class="form-control" required>
                                    <option value="">Seleccionar cliente</option>
                                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                        {{ cliente.nombre }}
                                    </option>
                                </select>
                                <div v-if="form.errors.cliente_id" class="text-danger small">{{ form.errors.cliente_id
                                }}</div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3 bg-light p-3 rounded mx-1">
                            <div class="col-md-4">
                                <label class="form-label">Producto</label>
                                <select v-model="productoSeleccionado" class="form-control"
                                    :disabled="!!servicioSeleccionado">
                                    <option value="">Seleccionar producto</option>
                                    <option v-for="p in productos" :key="p.id" :value="p">
                                        {{ p.nombre }} — {{ formatCurrency(p.precio) }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Servicio</label>
                                <select v-model="servicioSeleccionado" class="form-control"
                                    :disabled="!!productoSeleccionado">
                                    <option value="">Seleccionar servicio</option>
                                    <option v-for="s in servicios" :key="s.id" :value="s">
                                        {{ s.descripcion }} — {{ formatCurrency(s.precio) }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Cantidad</label>
                                <input type="number" v-model="cantidad" min="1" class="form-control">
                            </div>

                            <div class="col-md-2 align-self-end">
                                <button type="button" class="btn btn-success w-100" @click="agregarDetalle">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>P/U</th>
                                        <th>Subtotal</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(detalle, index) in form.detalles" :key="index">
                                        <td>{{ detalle.tipo }}</td>
                                        <td>{{ detalle.nombre }}</td>
                                        <td>{{ detalle.cantidad }}</td>
                                        <td>{{ formatCurrency(detalle.precio) }}</td>
                                        <td class="fw-bold">{{ formatCurrency(detalle.subtotal) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                @click="eliminarDetalle(index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="form.detalles.length === 0">
                                        <td colspan="6" class="text-center text-muted">No hay items agregados</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end fs-5">TOTAL:</th>
                                        <th colspan="2" class="fs-5">{{ formatCurrency(totalGeneral) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"
                                :disabled="form.processing">
                                <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
                                Guardar Venta
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 1050;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    overflow-y: auto;
    padding-top: 2rem;
}

.modal-dialog {
    width: 100%;
    max-width: 1140px;
    margin: 1.75rem auto;
}

.modal-content {
    position: relative;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: .3rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>