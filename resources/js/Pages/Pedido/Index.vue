<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NoPermiso from '@/Components/NoPermiso.vue';
import RegistrarPagoModal from './RegistrarPago.vue';
import PlanPagoModal from './PlanPago.vue';

const props = defineProps({
  pedidos: { type: Array, default: () => [] },
  clientes: { type: Array, default: () => [] },
  productos: { type: Array, default: () => [] },
});

const page = usePage();
const privilegios = computed(() => page.props.auth?.privilegios?.Pedido || {});
const canRead = computed(() => !!privilegios.value.leer);
const canAdd = computed(() => !!privilegios.value.agregar);
const canEdit = computed(() => !!privilegios.value.modificar);
const canDelete = computed(() => !!privilegios.value.borrar);

const showForm = ref(false);
const editando = ref(null);
const showDelete = ref(null);
const showDetails = ref(null);
const registrarPago = ref(null);
const planPago = ref(null);

const productoId = ref('');
const cantidad = ref(1);
const precioVenta = ref(0);

const hoy = () => new Date().toISOString().slice(0, 10);

const form = useForm({
  fecha_pedido: hoy(),
  id_cliente: '',
  estado_pedido: 'pendiente',
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
  precioVenta.value = producto ? Number(producto.precio_venta || 0) : 0;
}

function limpiarDetalleTemporal() {
  productoId.value = '';
  cantidad.value = 1;
  precioVenta.value = 0;
}

function limpiarFormulario() {
  form.fecha_pedido = hoy();
  form.id_cliente = '';
  form.estado_pedido = 'pendiente';
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

function abrirEditar(pedido) {
  editando.value = pedido;
  form.fecha_pedido = pedido.fecha_pedido || hoy();
  form.id_cliente = pedido.id_cliente || '';
  form.estado_pedido = pedido.estado_pedido || 'pendiente';
  form.observacion = pedido.observacion || '';
  form.detalles = (pedido.detalles || []).map((detalle) => ({
    id_producto: detalle.id_producto,
    nombre: detalle.producto?.nombre || `Producto #${detalle.id_producto}`,
    cantidad: Number(detalle.cantidad || 1),
    precio_venta: Number(detalle.precio_venta || 0),
    subtotal: Number(detalle.subtotal || 0),
  }));
  form.clearErrors();
  limpiarDetalleTemporal();
  showForm.value = true;
}

function agregarDetalle() {
  const producto = props.productos.find((p) => Number(p.id) === Number(productoId.value));
  const cant = Number(cantidad.value || 0);
  const precio = Number(precioVenta.value || 0);

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
    existente.precio_venta = precio;
    existente.subtotal = existente.cantidad * existente.precio_venta;
  } else {
    form.detalles.push({
      id_producto: producto.id,
      nombre: producto.nombre,
      cantidad: cant,
      precio_venta: precio,
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
    alert('Debe agregar al menos un producto al pedido.');
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
    form.put(route('pedido.update', editando.value.id), opciones);
  } else {
    form.post(route('pedido.store'), opciones);
  }
}


function tienePlanActivo(pedido) {
  return !!pedido?.plan_pago;
}

function totalPagado(pedido) {
  return (pedido?.pagos || [])
    .filter((pago) => pago.state === 'a' && pago.estado_pago === 'pagado')
    .reduce((acc, pago) => acc + Number(pago.monto || 0), 0);
}

function saldoPedido(pedido) {
  return Math.max(0, Number(pedido?.total || 0) - totalPagado(pedido));
}

function eliminarPedido() {
  if (!showDelete.value) return;
  router.delete(route('pedido.destroy', showDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => showDelete.value = null,
  });
}
</script>

<template>
  <Head title="Gestión de pedidos" />
  <AppLayout title="Gestión de pedidos">
    <section class="content" v-if="canRead">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title mb-0"><i class="fas fa-shopping-cart mr-2"></i><b>GESTIONAR PEDIDOS</b></h1>
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
                <th>CLIENTE</th>
                <th>TOTAL</th>
                <th>ESTADO</th>
                <th>OBSERVACIÓN</th>
                <th class="text-center">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="pedido in pedidos" :key="pedido.id">
                <td>{{ pedido.id }}</td>
                <td>{{ pedido.fecha_pedido }}</td>
                <td>{{ nombreCompleto(pedido.cliente) }}</td>
                <td>{{ formatMoney(pedido.total) }}</td>
                <td>{{ pedido.estado_pedido }}</td>
                <td>{{ pedido.observacion || '—' }}</td>
                <td class="text-center text-nowrap">
                  <button class="btn btn-primary btn-sm mr-1" @click="showDetails = pedido">Ver detalle</button>
                  <button v-if="canEdit" class="btn btn-success btn-sm mr-1" @click="registrarPago = pedido">
                    Registrar pago
                  </button>
                  <button v-if="canEdit" class="btn btn-info btn-sm mr-1" :disabled="tienePlanActivo(pedido) || pedido.estado_pedido === 'pagado'" @click="planPago = pedido">
                    Plan de pago
                  </button>
                  <button v-if="canEdit" class="btn btn-warning btn-sm mr-1" @click="abrirEditar(pedido)">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button v-if="canDelete" class="btn btn-danger btn-sm" @click="showDelete = pedido">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="!pedidos.length">
                <td colspan="7" class="text-center text-muted py-4">No existen pedidos activos.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="showForm" class="modal-mask">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
              <h5 class="modal-title">{{ editando ? 'Editar pedido' : 'Agregar pedido' }}</h5>
              <button type="button" class="btn-close" @click="showForm = false"></button>
            </div>

            <form @submit.prevent="guardar">
              <div class="modal-body">
                <div class="row mb-3">
                  <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha</label>
                    <input type="date" class="form-control" v-model="form.fecha_pedido" required>
                    <div v-if="form.errors.fecha_pedido" class="text-danger small">{{ form.errors.fecha_pedido }}</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label fw-bold">Cliente</label>
                    <select class="form-control" v-model="form.id_cliente">
                      <option value="">Seleccionar cliente</option>
                      <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                        {{ nombreCompleto(cliente) }}
                      </option>
                    </select>
                    <div v-if="form.errors.id_cliente" class="text-danger small">{{ form.errors.id_cliente }}</div>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label fw-bold">Estado</label>
                    <input type="text" class="form-control" v-model="form.estado_pedido" required>
                    <div v-if="form.errors.estado_pedido" class="text-danger small">{{ form.errors.estado_pedido }}</div>
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
                        {{ producto.nombre }} — {{ formatMoney(producto.precio_venta) }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" min="1" class="form-control" v-model="cantidad">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Precio venta</label>
                    <input type="number" min="0" step="0.01" class="form-control" v-model="precioVenta">
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
                        <th>Precio venta</th>
                        <th>Subtotal</th>
                        <th style="width: 55px;"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(detalle, index) in form.detalles" :key="index">
                        <td>{{ detalle.nombre }}</td>
                        <td>{{ detalle.cantidad }}</td>
                        <td>{{ formatMoney(detalle.precio_venta) }}</td>
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
                  {{ editando ? 'Actualizar pedido' : 'Guardar pedido' }}
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
              <h5 class="modal-title">Detalle del pedido #{{ showDetails.id }}</h5>
              <button type="button" class="btn-close" @click="showDetails = null"></button>
            </div>
            <div class="modal-body">
              <p><b>Cliente:</b> {{ nombreCompleto(showDetails.cliente) }}</p>
              <p><b>Fecha:</b> {{ showDetails.fecha_pedido }} | <b>Estado:</b> {{ showDetails.estado_pedido }}</p>
              <p><b>Pagado:</b> {{ formatMoney(totalPagado(showDetails)) }} | <b>Saldo:</b> {{ formatMoney(saldoPedido(showDetails)) }}</p>
              <table class="table table-bordered table-sm">
                <thead class="table-light">
                  <tr><th>Producto</th><th>Cantidad</th><th>Precio venta</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                  <tr v-for="detalle in showDetails.detalles || []" :key="detalle.id">
                    <td>{{ detalle.producto?.nombre || ('Producto #' + detalle.id_producto) }}</td>
                    <td>{{ detalle.cantidad }}</td>
                    <td>{{ formatMoney(detalle.precio_venta) }}</td>
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

              <div v-if="showDetails.plan_pago" class="mt-4">
                <h6 class="fw-bold">Plan de pago</h6>
                <table class="table table-bordered table-sm">
                  <thead class="table-light">
                    <tr><th>#</th><th>Monto</th><th>Vencimiento</th><th>Estado</th></tr>
                  </thead>
                  <tbody>
                    <tr v-for="cuota in showDetails.plan_pago.cuotas || []" :key="cuota.id">
                      <td>{{ cuota.numero_cuota }}</td>
                      <td>{{ formatMoney(cuota.monto) }}</td>
                      <td>{{ cuota.fecha_vencimiento }}</td>
                      <td>{{ cuota.estado_cuota }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <RegistrarPagoModal v-if="registrarPago" :show="!!registrarPago" :pedido="registrarPago" @close="registrarPago = null" />
      <PlanPagoModal v-if="planPago" :show="!!planPago" :pedido="planPago" @close="planPago = null" />

      <div v-if="showDelete" class="modal-mask">
        <div class="modal-container text-center">
          <h5>¿Eliminar pedido?</h5>
          <p>Esta acción desactivará el pedido <strong>#{{ showDelete.id }}</strong> y sus detalles.</p>
          <div class="modal-footer text-end">
            <button class="btn btn-secondary" @click="showDelete = null">Cancelar</button>
            <button class="btn btn-danger" @click="eliminarPedido">Eliminar</button>
          </div>
        </div>
      </div>
    </section>

    <NoPermiso v-else mensaje="No tienes permisos para ver la gestión de pedidos." />
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
