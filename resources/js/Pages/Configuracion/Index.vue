<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import NoPermiso from "@/Components/NoPermiso.vue";


const props = defineProps({
    datos: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Administracion" && p.state === "a" && p[type] === true
    );
}
const can = (funcionalidad) => {
    return page.props.auth.privilegios?.[funcionalidad]?.leer;
};

const canEdit = computed(() => hasPermission("modificar"));
const showEditNombre = ref(null);
const showEditDireccion = ref(null);
const showEditCorreo = ref(null);
const showEditTelefono = ref(null);
const nombreEmpresa = ref(props.datos.nombre);
const direccionEmpresa = ref(props.datos.direccion);
const correoEmpresa = ref(props.datos.correo);
const telefonoEmpresa = ref(props.datos.telefono);

function updateNombre() {
    router.put(
        route("empresa.nombre", props.datos.id),
        { nombre: nombreEmpresa.value },
        {
            onSuccess: () => {
                showEditNombre.value = null;
            }
        }
    );
}
function updateDireccion() {
    router.put(
        route("empresa.direccion", props.datos.id),
        { direccion: direccionEmpresa.value },
        {
            onSuccess: () => {
                showEditDireccion.value = null;
            }
        }
    );
}
function updateCorreo() {
    router.put(
        route("empresa.correo", props.datos.id),
        { correo: correoEmpresa.value },
        {
            onSuccess: () => {
                showEditCorreo.value = null;
            }
        }
    );
}
function updateTelefono() {
    router.put(
        route("empresa.telefono", props.datos.id),
        { telefono: telefonoEmpresa.value },
        {
            onSuccess: () => {
                showEditTelefono.value = null;
            }
        }
    );
}

</script>

<template>
    <AppLayout>

        <section class="content" v-if="can('Administracion')">
            <div class="card-header d-flex justify-content-between align-items-center">

                <h1 class="card-title mb-0">
                    <i class="fas fa-cog mr-1"></i>
                    <b>CONFIGURACIÓN </b>
                </h1>

            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray">NOMBRE DE LA EMPRESA</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{
                            datos.nombre }}</label>
                        <a class="btn btn-link" @click.prevent="showEditNombre = datos.nombre">
                            <i class="fa fa-pen" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <hr class="">

                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray">DIRECCIÓN</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{
                            datos.direccion }}</label>
                        <a class="btn btn-link" @click.prevent="showEditDireccion = datos.direccion">
                            <i class="fa fa-pen" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <hr class="">

                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray">CORREO
                        ELECTRONICO</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{
                            datos.correo }}</label>
                        <a class="btn btn-link" @click.prevent="showEditCorreo = datos.correo">
                            <i class="fa fa-pen" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <hr class="">

                <div class="mb-3">
                    <label for="nombre" class="form-label text-darkgray">TELÉFONO</label>
                    <div class="input-group">
                        <label class="form-control-plaintext" id="nombre" style="font-weight: normal">{{
                            datos.telefono }}</label>
                        <a class="btn btn-link" @click.prevent="showEditTelefono = datos.telefono">
                            <i class="fa fa-pen" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

            </div>


            <div v-if="showEditNombre" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Editar Nombre de la empresa</h5>
                        <button type="button" class="btn-close" @click="showEditNombre = null"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="nombre" class="form-label">Nombre de la empresa</label>
                            <input class="form-control" v-model="nombreEmpresa" placeholder="Nombre" />
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showEditNombre = null">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="updateNombre">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showEditDireccion" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Editar Dirección</h5>
                        <button type="button" class="btn-close" @click="showEditDireccion = null"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="direccion" class="form-label">Dirección</label>
                            <input class="form-control" v-model="direccionEmpresa" placeholder="Dirección" />
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showEditDireccion = null">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="updateDireccion">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showEditCorreo" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Editar Correo</h5>
                        <button type="button" class="btn-close" @click="showEditCorreo = null"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="correo" class="form-label">Correo</label>
                            <input class="form-control" v-model="correoEmpresa" placeholder="Correo" />
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showEditCorreo = null">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="updateCorreo">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showEditTelefono" class="modal-mask">
                <div class="modal-container">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">Editar Teléfono</h5>
                        <button type="button" class="btn-close" @click="showEditTelefono = null"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input class="form-control" v-model="telefonoEmpresa" placeholder="Teléfono" />
                        </div>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showEditTelefono = null">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="updateTelefono">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>

        </section>
        <NoPermiso v-else mensaje="No tienes permiso para ver las configuraciones." />

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
