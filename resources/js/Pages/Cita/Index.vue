<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, usePage, useForm } from "@inertiajs/vue3";
import NoPermiso from "@/Components/NoPermiso.vue";

// === PROPS desde el Controller Inertia ===
const props = defineProps({
    citas: Array,
    clientes: Array,
    medicos: Array,
    secretarias: Array,
    servicios: Array,
    num: Number,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Cita" && p.state === "a" && p[type] === true
    );
}
const can = (funcionalidad) => {
    return page.props.auth.privilegios?.[funcionalidad]?.leer;
};

const canAdd = computed(() => hasPermission("agregar"));
const canEdit = computed(() => hasPermission("modificar"));
const canDelete = computed(() => hasPermission("borrar"));

const showAdd = ref(false);
const showEdit = ref(null);
const showDelete = ref(null);

const errors = computed(() => page.props.errors);

const form = useForm({
    fecha_hora: "",
    id_cliente: null,
    id_medico: null,
    id_secretaria: null,
    id_servicio: null,
    estado: "pendiente",
});

const editCita = ref(null);
function storeCita() {
    form.post(route("cita.store"), {
        onSuccess: () => {
            showAdd.value = false;
            form.reset();
        },
    });
}

function updateCita() {
    router.put(
        route("cita.update", editCita.value.id),
        editCita.value,
        {
            onSuccess: () => {
                showEdit.value = null;
            }
        }
    );
}

function deleteCita(cita) {
    router.delete(route("cita.destroy", cita.id), {
        onSuccess: () => {
            showDelete.value = null;
        }
    });
}

// ---- DATATABLES ----
let dataTable = null;
const tableKey = ref(0);

const initDataTable = () => {
    if (window.$) {
        dataTable = window.$("#citas").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    }
};

onMounted(() => {
    initDataTable();
});

onUnmounted(() => {
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
});

watch(() => props.citas, async () => {
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
    tableKey.value++;
    await nextTick();
    initDataTable();
});
</script>

<template>
    <AppLayout title="G. Cita">

        <section class="content" v-if="can('Cita')">
            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center">

                <h1 class="card-title mb-0">
                    <i class="fas fa-clock mr-2"></i><b>GESTIONAR CITAS</b>
                </h1>

                <div class="d-flex align-items-center ml-auto">
                    <button v-if="canAdd" class="btn btn-success" @click="showAdd = true">
                        <i class="fa fa-plus"></i>&nbsp; Agregar
                    </button>
                </div>

            </div>

            <!-- TABLA -->
            <div class="card table-responsive mt-3">
                <div class="card-body">
                    <table class="table table-hover" id="citas" :key="tableKey">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>FECHA / HORA</th>
                                <th>CLIENTE</th>
                                <th>MEDICO</th>
                                <th>SECRETARIA</th>
                                <th>SERVICIO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cita in citas" :key="cita.id">
                                <td>{{ cita.id }}</td>
                                <td>{{ cita.fecha_hora }}</td>
                                <td>{{ cita.cliente?.nombre || '-' }}</td>
                                <td>{{ cita.medico?.nombre || '-' }}</td>
                                <td>{{ cita.secretaria?.nombre || '-' }}</td>
                                <td>{{ cita.servicio?.nombre || '-' }}</td>
                                <td>{{ cita.estado }}</td>
                                <td>
                                    <a v-if="canEdit" href="#" @click.prevent="
                                        showEdit = cita.id;
                                    editCita = { ...cita };
                                    ">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;
                                    <a v-if="canDelete" href="#" @click.prevent="showDelete = cita">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL AGREGAR -->
            <div v-if="showAdd" class="modal-mask" style="overflow-y: auto;">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Agregar Cita</h5>
                        <button type="button" class="btn-close" @click="showAdd = false"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Fecha / Hora</label>
                            <input type="datetime-local" class="form-control" required v-model="form.fecha_hora" />
                            <p v-if="form.errors.fecha_hora" class="text-danger text-sm">{{ form.errors.fecha_hora }}
                            </p>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Cliente</label>
                            <select class="form-control" v-model="form.id_cliente">
                                <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                            </select>
                            <p v-if="form.errors.id_cliente" class="text-danger text-sm">{{ form.errors.id_cliente }}
                            </p>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Medico</label>
                            <select class="form-control" v-model="form.id_medico">
                                <option v-for="m in medicos" :key="m.id" :value="m.id">{{ m.nombre }}</option>
                            </select>
                            <p v-if="form.errors.id_medico" class="text-danger text-sm">{{ form.errors.id_medico }}</p>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Secretaria</label>
                            <select class="form-control" v-model="form.id_secretaria">
                                <option v-for="s in secretarias" :key="s.id" :value="s.id">{{ s.nombre }}</option>
                            </select>
                            <p v-if="form.errors.id_secretaria" class="text-danger text-sm">{{ form.errors.id_secretaria
                            }}</p>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Servicio</label>
                            <select class="form-control" v-model="form.id_servicio">
                                <option v-for="s in servicios" :key="s.id" :value="s.id">{{ s.nombre }}</option>
                            </select>
                            <p v-if="form.errors.id_servicio" class="text-danger text-sm">{{ form.errors.id_servicio }}
                            </p>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Estado</label>
                            <select class="form-control" v-model="form.estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showAdd = false">Cancelar</button>
                        <button class="btn btn-primary" @click="storeCita" :disabled="form.processing">
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Guardar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL EDITAR -->
            <div v-if="showEdit" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Editar Cita</h5>
                        <button type="button" class="btn-close" @click="showEdit = null"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Fecha / Hora</label>
                            <input type="datetime-local" class="form-control" v-model="editCita.fecha_hora" />
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Cliente</label>
                            <select class="form-control" v-model="editCita.id_cliente">
                                <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Medico</label>
                            <select class="form-control" v-model="editCita.id_medico">
                                <option v-for="m in medicos" :key="m.id" :value="m.id">{{ m.nombre }}</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Secretaria</label>
                            <select class="form-control" v-model="editCita.id_secretaria">
                                <option v-for="s in secretarias" :key="s.id" :value="s.id">{{ s.nombre }}</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Servicio</label>
                            <select class="form-control" v-model="editCita.id_servicio">
                                <option v-for="s in servicios" :key="s.id" :value="s.id">{{ s.nombre }}</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Estado</label>
                            <select class="form-control" v-model="editCita.estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showEdit = null">Cancelar</button>
                        <button class="btn btn-primary" @click="updateCita">Guardar cambios</button>
                    </div>
                </div>
            </div>

            <!-- MODAL ELIMINAR -->
            <div v-if="showDelete" class="modal-mask">
                <div class="modal-container text-center">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">¿Eliminar Cita?</h5>
                        <button type="button" class="btn-close" @click="showDelete = null"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar la cita del cliente <strong>{{
                            showDelete.cliente?.nombre
                                }}</strong>?</p>
                    </div>
                    <div class="modal-footer text-end">
                        <button class="btn btn-secondary" @click="showDelete = null">Cancelar</button>
                        <button class="btn btn-danger" @click="deleteCita(showDelete)">Eliminar</button>
                    </div>
                </div>
            </div>

        </section>
        <NoPermiso v-else mensaje="No tienes permiso para ver las citas." />

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
    align-items: center;
    justify-content: center;
    display: flex;
    width: 100vw;
}

.modal-container {
    background: white;
    width: min(500px, 90%);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
}
</style>
