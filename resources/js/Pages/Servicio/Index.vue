<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import NoPermiso from "@/Components/NoPermiso.vue";

const props = defineProps({
    servicios: Array,
    num: Number,
    promociones: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const errors = computed(() => page.props.errors);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Usuario" && p.state === "a" && p[type] === true
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

const newServicio = ref({ nombre: "", descripcion: "", precio: "", duracion: "", id_promocion: "" });
const editServicio = ref({ id: null, nombre: "", descripcion: "", precio: "", duracion: "", id_promocion: "" });

function storeServicio() {
    router.post(
        route("servicio.store"),
        newServicio.value,
        {
            onSuccess: () => {
                showAdd.value = false;
                newServicio.value = { nombre: "", descripcion: "", precio: "", duracion: "", id_promocion: "" };
            },
            onError: (e) => {
                console.log(e);
            }
        }
    );
}

function updateServicio() {
    router.put(
        route("servicio.update", editServicio.value.id),
        editServicio.value,
        {
            onSuccess: () => {
                showEdit.value = null;
            }
        }
    );
}

function deleteServicio(id) {
    router.delete(route("servicio.destroy", id), {
        onSuccess: () => {
            showDelete.value = null;
        }
    });
}

let dataTable = null;

onMounted(() => {
    if (window.$) {
        dataTable = window.$("#servicios").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
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

    <Head title="G. Servicios" />
    <AppLayout>

        <section class="content" v-if="can('Servicio')">
            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center">

                <h1 class="card-title mb-0">
                    <i class="fas fa-clock mr-2"></i><b>GESTIONAR SERVICIOS</b>
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
                    <table class="table table-hover" id="servicios">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>DESCRIPCION</th>
                                <th>PRECIO</th>
                                <th>DURACION</th>
                                <th>PROMOCION</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="servicio in servicios" :key="servicio.id">
                                <td>{{ servicio.id }}</td>
                                <td>{{ servicio.nombre }}</td>
                                <td>{{ servicio.descripcion }}</td>
                                <td>
                                    <template v-if="servicio.promocion !== null">
                                        <template v-if="servicio.precio !== servicio.precioConDescuento">
                                            <span style="text-decoration: line-through; color: red;">
                                                ${{ Number(servicio.precio).toFixed(2) }}
                                            </span>
                                            <span style="color: green;">
                                                ${{ Number(servicio.precioConDescuento).toFixed(2) }}
                                            </span>
                                        </template>
                                        <template v-else>
                                            ${{ Number(servicio.precio).toFixed(2) }}
                                        </template>
                                    </template>
                                    <template v-else>
                                        ${{ Number(servicio.precio).toFixed(2) }}
                                    </template>
                                </td>
                                <td>{{ servicio.duracion }}</td>
                                <td>{{ servicio.promocion?.nombre || 'N/A' }}</td>

                                <td>
                                    <a v-if="canEdit" href="#" @click.prevent="
                                        showEdit = servicio.id;
                                    editServicio = { ...servicio };
                                    ">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;
                                    <a v-if="canDelete" href="#" @click.prevent="showDelete = servicio">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL AGREGAR -->
            <div v-if="showAdd" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Agregar Servicio</h5>
                        <button type="button" class="btn-close" @click="showAdd = false"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input class="form-control" v-model="newServicio.nombre" placeholder="Nombre" />
                            <p v-if="errors.nombre" class="text-danger">{{ errors.nombre }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input class="form-control" v-model="newServicio.descripcion" placeholder="Descripción" />
                            <p v-if="errors.descripcion" class="text-danger">{{ errors.descripcion }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="   number" class="form-control" v-model="newServicio.precio"
                                placeholder="Precio" />
                            <p v-if="errors.precio" class="text-danger">{{ errors.precio }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="duracion" class="form-label">Duración</label>
                            <input type="number" class="form-control" v-model="newServicio.duracion"
                                placeholder="Duración" />
                            <p v-if="errors.duracion" class="text-danger">{{ errors.duracion }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="id_promocion" class="form-label">Promoción</label>
                            <select class="form-control" v-model="newServicio.id_promocion">
                                <option value="">Seleccionar Promoción</option>
                                <option v-for="promocion in promociones" :key="promocion.id" :value="promocion.id">
                                    {{ promocion.nombre }}
                                </option>
                            </select>
                            <p v-if="errors.id_promocion" class="text-danger">{{ errors.id_promocion }}</p>
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showAdd = false">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="storeServicio">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>


            <!-- MODAL EDITAR -->
            <div v-if="showEdit" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Editar Servicio</h5>
                        <button type="button" class="btn-close" @click="showEdit = null"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input class="form-control" v-model="editServicio.nombre" placeholder="Nombre" />
                            <p v-if="errors.nombre" class="text-danger">{{ errors.nombre }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input class="form-control" v-model="editServicio.descripcion" placeholder="Descripción" />
                            <p v-if="errors.descripcion" class="text-danger">{{ errors.descripcion }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" v-model="editServicio.precio"
                                placeholder="Precio" />
                            <p v-if="errors.precio" class="text-danger">{{ errors.precio }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="duracion" class="form-label">Duración</label>
                            <input type="number" class="form-control" v-model="editServicio.duracion"
                                placeholder="Duración" />
                            <p v-if="errors.duracion" class="text-danger">{{ errors.duracion }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="id_promocion" class="form-label">Promoción</label>
                            <select class="form-control" v-model="editServicio.id_promocion">
                                <option value="">Seleccionar Promoción</option>
                                <option v-for="promocion in promociones" :key="promocion.id" :value="promocion.id">
                                    {{ promocion.nombre }}
                                </option>
                            </select>
                            <p v-if="errors.id_promocion" class="text-danger">{{ errors.id_promocion }}</p>
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showEdit = null">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="updateServicio">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL ELIMINAR -->
            <div v-if="showDelete" class="modal-mask">
                <div class="modal-container text-center">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">¿Eliminar Servicio?</h5>
                        <button type="button" class="btn-close" @click="showDelete = null"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el servicio <strong>{{ showDelete.nombre }}</strong>?
                        </p>
                    </div>
                    <div class="modal-footer text-end">
                        <button class="btn btn-secondary" @click="showDelete = null">
                            Cancelar
                        </button>
                        <button class="btn btn-danger" @click="deleteServicio(showDelete)">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <NoPermiso v-else mensaje="No tienes permisos para ver los servicios." />

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
