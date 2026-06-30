<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NoPermiso from '@/Components/NoPermiso.vue';

const props = defineProps({
  compras: { type: Array, default: () => [] },
  proveedores: { type: Array, default: () => [] },
  productos: { type: Array, default: () => [] },
});

const page = usePage();
const privilegios = computed(() => page.props.auth?.privilegios?.Compra || {});
const canRead = computed(() => !!privilegios.value.leer);
const canAdd = computed(() => !!privilegios.value.agregar);
const canEdit = computed(() => !!privilegios.value.modificar);
const canDelete = computed(() => !!privilegios.value.borrar);

const showForm = ref(false);
const editando = ref(null);
const showDelete = ref(null);
const showDetails = ref(null);

const productoId = ref('');
const cantidad = ref(1);
const precioCompra = ref(0);

const hoy = () => new Date().toISOString().slice(0, 10);

const form = useForm({
  fecha_compra: hoy(),
  id_proveedor: '',
  estado_compra: 'registrada',
  observacion: '',
  detalles: [],
});

const totalGeneral = computed(() => form.detalles.reduce((acc, item) => acc + Number(item.subtotal || 0), 0));

function formatMoney(value) {
  return `Bs ${Number(value || 0).toFixed(2)}`;
}

function nombreCompleto(persona) {
  if (!persona) return 'No asignado';
  return `${persona.nombre || ''} ${persona.apellido || ''}`.trim() || `#${persona.id}`;
}

function seleccionarProducto() {
  const producto = props.productos.find((p) => Number(p.id) === Number(productoId.value));
  precioCompra.value = producto ? Number(producto.precio_compra || 0) : 0;
}

function limpiarDetalleTemporal() {
  productoId.value = '';
  cantidad.value = 1;
  precioCompra.value = 0;
}

function limpiarFormulario() {
  form.fecha_compra = hoy();
  form.id_proveedor = '';
  form.estado_compra = 'registrada';
  form.observacion = '';
  form.detalles = [];
  form.clearErrors();
  limpiarDetalleTemporal();
}

function abrirCrear() {
  editando.value = null;
  limpiarFormulario();
  showForm.value = true;
}

function abrirEditar(compra) {
  editando.value = compra;
  form.fecha_compra = compra.fecha_compra || hoy();
  form.id_proveedor = compra.id_proveedor || '';
  form.estado_compra = compra.estado_compra || 'registrada';
  form.observacion = compra.observacion || '';
  form.detalles = (compra.detalles || []).map((detalle) => ({
    id_producto: detalle.id_producto,
    nombre: detalle.producto?.nombre || `Producto #${detalle.id_producto}`,
    cantidad: Number(detalle.cantidad || 1),
    precio_compra: Number(detalle.precio_compra || 0),
    subtotal: Number(detalle.subtotal || 0),
  }));
  form.clearErrors();
  limpiarDetalleTemporal();
  showForm.value = true;
}

function agregarDetalle() {
  const producto = props.productos.find((p) => Number(p.id) === Number(productoId.value));
  const cant = Number(cantidad.value || 0);
  const precio = Number(precioCompra.value || 0);

  if (!producto) {
    alert('Seleccione un producto.');
    return;
  }
  if (cant < 1) {
    alert('La cantidad debe ser mayor o igual a 1.');
    return;
  }
  if (precio < 0) {
    alert('El precio no puede ser negativo.');
    return;
  }

  const existente = form.detalles.find((d) => Number(d.id_producto) === Number(producto.id));
  if (existente) {
    existente.cantidad = Number(existente.cantidad) + cant;
    existente.precio_compra = precio;
    existente.subtotal = existente.cantidad * existente.precio_compra;
  } else {
    form.detalles.push({
      id_producto: producto.id,
      nombre: producto.nombre,
      cantidad: cant,
      precio_compra: precio,
      subtotal: cant * precio,
    });
  }

  limpiarDetalleTemporal();
}

function eliminarDetalle(index) {
  form.detalles.splice(index, 1);
}

function guardar() {
  if (form.detalles.length === 0) {
    alert('Debe agregar al menos un producto a la compra.');
    return;
  }

  const opciones = {
    preserveScroll: true,
    onSuccess: () => {
      showForm.value = false;
      editando.value = null;
      limpiarFormulario();
    },
  };

  if (editando.value) {
    form.put(route('compra.update', editando.value.id), opciones);
  } else {
    form.post(route('compra.store'), opciones);
  }
}

function eliminarCompra() {
  if (!showDelete.value) return;
  router.delete(route('compra.destroy', showDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => showDelete.value = null,
  });
}
</script>

<template>
  <Head title="Gestión de compras" />
  <AppLayout title="Gestión de compras">
    <section class="content" v-if="canRead">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title mb-0"><i class="fas fa-truck-loading mr-2"></i><b>GESTIONAR COMPRAS</b></h1>
        <button v-if="canAdd" class="btn btn-success ml-auto" @click="abrirCrear">
          <i class="fa fa-plus"></i>&nbsp; Agregar
        </button>
      </div>

      <div class="card table-responsive mt-3">
        <div class="card-body">
          <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>FECHA</th>
                <th>PROVEEDOR</th>
                <th>TOTAL</th>
                <th>ESTADO</th>
                <th>OBSERVACIÓN</th>
                <th class="text-center">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="compra in compras" :key="compra.id">
                <td>{{ compra.id }}</td>
                <td>{{ compra.fecha_compra }}</td>
                <td>{{ nombreCompleto(compra.proveedor) }}</td>
                <td>{{ formatMoney(compra.total) }}</td>
                <td>{{ compra.estado_compra }}</td>
                <td>{{ compra.observacion || '—' }}</td>
                <td class="text-center text-nowrap">
                  <button class="btn btn-primary btn-sm mr-1" @click="showDetails = compra">Ver detalle</button>
                  <button v-if="canEdit" class="btn btn-warning btn-sm mr-1" @click="abrirEditar(compra)">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button v-if="canDelete" class="btn btn-danger btn-sm" @click="showDelete = compra">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="!compras.length">
                <td colspan="7" class="text-center text-muted py-4">No existen compras activas.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="showForm" class="modal-mask">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
              <h5 class="modal-title">{{ editando ? 'Editar compra' : 'Agregar compra' }}</h5>
              <button type="button" class="btn-close" @click="showForm = false"></button>
            </div>

            <form @submit.prevent="guardar">
              <div class="modal-body">
                <div class="row mb-3">
                  <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha</label>
                    <input type="date" class="form-control" v-model="form.fecha_compra" required>
                    <div v-if="form.errors.fecha_compra" class="text-danger small">{{ form.errors.fecha_compra }}</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label fw-bold">Proveedor</label>
                    <select class="form-control" v-model="form.id_proveedor">
                      <option value="">Seleccionar proveedor</option>
                      <option v-for="proveedor in proveedores" :key="proveedor.id" :value="proveedor.id">
                        {{ nombreCompleto(proveedor) }}
                      </option>
                    </select>
                    <div v-if="form.errors.id_proveedor" class="text-danger small">{{ form.errors.id_proveedor }}</div>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label fw-bold">Estado</label>
                    <input type="text" class="form-control" v-model="form.estado_compra" required>
                    <div v-if="form.errors.estado_compra" class="text-danger small">{{ form.errors.estado_compra }}</div>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label fw-bold">Total</label>
                    <input type="text" class="form-control" :value="formatMoney(totalGeneral)" disabled>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Observación</label>
                  <textarea class="form-control" rows="2" v-model="form.observacion"></textarea>
                  <div v-if="form.errors.observacion" class="text-danger small">{{ form.errors.observacion }}</div>
                </div>

                <div class="row mb-3 bg-light p-3 rounded mx-1">
                  <div class="col-md-5">
                    <label class="form-label">Producto</label>
                    <select v-model="productoId" class="form-control" @change="seleccionarProducto">
                      <option value="">Seleccionar producto</option>
                      <option v-for="producto in productos" :key="producto.id" :value="producto.id">
                        {{ producto.nombre }} — {{ formatMoney(producto.precio_compra) }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" min="1" class="form-control" v-model="cantidad">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Precio compra</label>
                    <input type="number" min="0" step="0.01" class="form-control" v-model="precioCompra">
                  </div>
                  <div class="col-md-2 align-self-end">
                    <button type="button" class="btn btn-success w-100" @click="agregarDetalle">
                      <i class="fas fa-plus"></i> Agregar
                    </button>
                  </div>
                </div>

                <div v-if="form.errors.detalles" class="alert alert-danger py-2">{{ form.errors.detalles }}</div>

                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-sm">
                    <thead class="table-light">
                      <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio compra</th>
                        <th>Subtotal</th>
                        <th style="width: 55px;"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(detalle, index) in form.detalles" :key="index">
                        <td>{{ detalle.nombre }}</td>
                        <td>{{ detalle.cantidad }}</td>
                        <td>{{ formatMoney(detalle.precio_compra) }}</td>
                        <td class="fw-bold">{{ formatMoney(detalle.subtotal) }}</td>
                        <td class="text-center">
                          <button type="button" class="btn btn-danger btn-sm" @click="eliminarDetalle(index)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="form.detalles.length === 0">
                        <td colspan="5" class="text-center text-muted">No hay productos agregados.</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-end">TOTAL:</th>
                        <th colspan="2">{{ formatMoney(totalGeneral) }}</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" @click="showForm = false">Cancelar</button>
                <button type="submit" class="btn btn-primary" :disabled="form.processing">
                  <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
                  {{ editando ? 'Actualizar compra' : 'Guardar compra' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div v-if="showDetails" class="modal-mask">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
              <h5 class="modal-title">Detalle de la compra #{{ showDetails.id }}</h5>
              <button type="button" class="btn-close" @click="showDetails = null"></button>
            </div>
            <div class="modal-body">
              <p><b>Proveedor:</b> {{ nombreCompleto(showDetails.proveedor) }}</p>
              <p><b>Fecha:</b> {{ showDetails.fecha_compra }} | <b>Estado:</b> {{ showDetails.estado_compra }}</p>
              <table class="table table-bordered table-sm">
                <thead class="table-light">
                  <tr><th>Producto</th><th>Cantidad</th><th>Precio compra</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                  <tr v-for="detalle in showDetails.detalles || []" :key="detalle.id">
                    <td>{{ detalle.producto?.nombre || ('Producto #' + detalle.id_producto) }}</td>
                    <td>{{ detalle.cantidad }}</td>
                    <td>{{ formatMoney(detalle.precio_compra) }}</td>
                    <td>{{ formatMoney(detalle.subtotal) }}</td>
                  </tr>
                  <tr v-if="!(showDetails.detalles || []).length">
                    <td colspan="4" class="text-center text-muted">No hay detalle registrado.</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr><th colspan="3" class="text-end">TOTAL:</th><th>{{ formatMoney(showDetails.total) }}</th></tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-if="showDelete" class="modal-mask">
        <div class="modal-container text-center">
          <h5>¿Eliminar compra?</h5>
          <p>Esta acción desactivará la compra <strong>#{{ showDelete.id }}</strong> y sus detalles.</p>
          <div class="modal-footer text-end">
            <button class="btn btn-secondary" @click="showDelete = null">Cancelar</button>
            <button class="btn btn-danger" @click="eliminarCompra">Eliminar</button>
          </div>
        </div>
      </div>
    </section>

    <NoPermiso v-else mensaje="No tienes permisos para ver la gestión de compras." />
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
  align-items: flex-start;
  justify-content: center;
  overflow-y: auto;
  padding-top: 2rem;
}
.modal-dialog {
  width: 100%;
  margin: 1.75rem auto;
}
.modal-content, .modal-container {
  background: white;
  border-radius: 8px;
  padding: 20px;
}
.modal-container {
  width: min(420px, 94%);
}
</style>
