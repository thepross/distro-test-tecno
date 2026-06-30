<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { usePage, router, Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import RegistrarPagoModal from "./RegistrarPago.vue";
import PlanPagoModal from "./PlanPago.vue";
import AgregarVentaModal from "./Create.vue";
import ShowVentaModal from "./Show.vue";
import NoPermiso from "@/Components/NoPermiso.vue";

const props = defineProps({
    ventas: Array,
    clientes: Array,
    productos: Array,
    servicios: Array,
    num: Number,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Venta" && p.state === "a" && p[type] === true
    );
}

const can = (funcionalidad) => {
    return page.props.auth.privilegios?.[funcionalidad]?.leer;
};

function hasPlan(ventaId) {
    const venta = props.ventas.find(v => v.id === ventaId);
    return venta && venta.planes && venta.planes.length === 0;
}
const canAdd = computed(() => hasPermission("agregar"));
const canEdit = computed(() => hasPermission("modificar"));
const canDelete = computed(() => hasPermission("borrar"));
const isCliente = computed(() => user.value?.rol?.nombre === 'Cliente');

const showCreate = ref(false);
const verDetalles = ref(null);
const registrarPago = ref(null);
const planPago = ref(null);
const ventaToDelete = ref(null);

function deleteVenta() {
    if (!ventaToDelete.value) return;
    router.delete(route("venta.destroy", ventaToDelete.value.id), {
        onSuccess: () => ventaToDelete.value = null
    });
}

let dataTable = null;
onMounted(() => {
    if (window.$) {
        dataTable = window.$("#ventas").DataTable({
            responsive: true, lengthChange: true, autoWidth: false,
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
        });
    }
});
onUnmounted(() => {
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
});
</script>

<template>

    <Head title="G. Ventas" />
    <AppLayout>
        <section class="content" v-if="can('Venta')">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0"><i class="fas fa-clock mr-2"></i><b>GESTIONAR VENTAS</b></h1>
                <div class="d-flex align-items-center ml-auto">
                    <button v-if="canAdd" class="btn btn-success" @click="showCreate = true">
                        <i class="fa fa-plus"></i>&nbsp; Agregar
                    </button>
                </div>
            </div>

            <div class="card table-responsive mt-3">
                <div class="card-body">
                    <table class="table table-hover" id="ventas">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>CLIENTE</th>
                                <th>REGISTRADO POR</th>
                                <th>FECHA</th>
                                <th>TOTAL</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in ventas" :key="venta.id">
                                <td>{{ venta.id }}</td>
                                <td>{{ venta.cliente?.nombre || 'No asignado' }}</td>
                                <td>{{ venta.usuario?.nombre || 'No asignado' }}</td>
                                <td>{{ venta.fecha_venta }}</td>
                                <td>{{ Number(venta.total).toFixed(2) }}</td>
                                <td>{{ venta.estado }}</td>
                                <td class="">
                                    <button class="btn btn-primary btn-sm" href="#"
                                        @click.prevent="verDetalles = venta">Ver
                                        Detalles
                                    </button>
                                    <template v-if="!isCliente">
                                        <button class="btn btn-success btn-sm" href="#"
                                            :disabled="venta.estado === 'pagado'"
                                            @click.prevent="registrarPago = venta">Registrar Pago
                                        </button>
                                        <button class="btn btn-warning btn-sm" href="#"
                                            @click.prevent="planPago = venta" :disabled="!hasPlan(venta.id)">Plan de
                                            Pago
                                        </button>
                                        <button class="btn btn-danger btn-sm" href="#"
                                            @click.prevent="ventaToDelete = venta"><i class="fa fa-trash"></i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <AgregarVentaModal :show="showCreate" :clientes="clientes" :productos="productos" :servicios="servicios"
                @close="showCreate = false" />

            <ShowVentaModal v-if="verDetalles" :show="!!verDetalles" :venta="verDetalles" @close="verDetalles = null" />

            <RegistrarPagoModal v-if="registrarPago" :show="!!registrarPago" :venta="registrarPago"
                @close="registrarPago = null" />
            <PlanPagoModal v-if="planPago" :show="!!planPago" :venta="planPago" @close="planPago = null" />



            <div v-if="ventaToDelete" class="modal-mask">
                <div class="modal-container text-center">
                    <h5>¿Eliminar Venta?</h5>
                    <p>¿Deseas eliminar a la venta con ID: <strong>{{ ventaToDelete.id }}</strong>?</p>
                    <div class="modal-footer text-end">
                        <button class="btn btn-secondary" @click="ventaToDelete = null">Cancelar</button>
                        <button class="btn btn-danger" @click="deleteVenta">Eliminar</button>
                    </div>
                </div>
            </div>
        </section>
        <NoPermiso v-else mensaje="No tienes permisos para ver las ventas." />

    </AppLayout>
</template>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.35);
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-container {
    background: white;
    width: min(400px, 90%);
    border-radius: 8px;
    padding: 20px;
}
</style>