<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Chart, registerables } from 'chart.js';
import NoPermiso from '@/Components/NoPermiso.vue';

Chart.register(...registerables);

const props = defineProps({
  cantidadPedidos: Number,
  cantidadCompras: Number,
  cantidadProductos: Number,
  cantidadUsuarios: Number,
  totalVendido: Number,
  totalComprado: Number,
  totalPagado: Number,
  cuotasPendientes: Number,
  cantidadVisitas: Number,
  mes: Array,
  cantidad: Array,
  productosTop: Array,
  stockBajo: Array,
  loginAceptados: Number,
  loginFallados: Number,
  ultimosAccesos: Array,
  recursosMasAccedidos: Array,
});

const page = usePage();
const graficoPedidosRef = ref(null);
const graficoRecursosRef = ref(null);

const can = (funcionalidad) => page.props.auth.privilegios?.[funcionalidad]?.leer;
const money = (value) => `Bs ${Number(value || 0).toFixed(2)}`;

onMounted(() => {
  if (!can('Reportes')) return;

  if (graficoPedidosRef.value && props.mes?.length) {
    new Chart(graficoPedidosRef.value, {
      type: 'bar',
      data: {
        labels: props.mes,
        datasets: [{ label: 'Pedidos por mes', data: props.cantidad, borderWidth: 1 }],
      },
      options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
    });
  }

  if (graficoRecursosRef.value && props.recursosMasAccedidos?.length) {
    new Chart(graficoRecursosRef.value, {
      type: 'bar',
      data: {
        labels: props.recursosMasAccedidos.map(item => item.nombre),
        datasets: [{ label: 'Visitas por recurso', data: props.recursosMasAccedidos.map(item => item.visitas), borderWidth: 1 }],
      },
      options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
    });
  }
});
</script>

<template>
  <Head title="Reportes - Distribuidora Carla" />
  <AppLayout>
    <section class="content" v-if="can('Reportes')">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info"><div class="inner"><h3>{{ cantidadPedidos ?? 0 }}</h3><p>Pedidos registrados</p></div><div class="icon"><i class="ion ion-bag"></i></div></div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success"><div class="inner"><h3>{{ money(totalVendido) }}</h3><p>Total en pedidos</p></div><div class="icon"><i class="ion ion-stats-bars"></i></div></div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning"><div class="inner"><h3>{{ cantidadProductos ?? 0 }}</h3><p>Productos activos</p></div><div class="icon"><i class="ion ion-cube"></i></div></div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger"><div class="inner"><h3>{{ cuotasPendientes ?? 0 }}</h3><p>Cuotas pendientes</p></div><div class="icon"><i class="ion ion-alert-circled"></i></div></div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary"><div class="inner"><h3>{{ cantidadCompras ?? 0 }}</h3><p>Compras registradas</p></div><div class="icon"><i class="ion ion-archive"></i></div></div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary"><div class="inner"><h3>{{ money(totalComprado) }}</h3><p>Total comprado</p></div><div class="icon"><i class="ion ion-card"></i></div></div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success"><div class="inner"><h3>{{ money(totalPagado) }}</h3><p>Pagos recibidos</p></div><div class="icon"><i class="ion ion-cash"></i></div></div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-dark"><div class="inner"><h3>{{ cantidadUsuarios ?? 0 }}</h3><p>Usuarios activos</p></div><div class="icon"><i class="ion ion-person"></i></div></div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="small-box bg-success"><div class="inner"><h3>{{ loginAceptados ?? 0 }}</h3><p>Login aceptados</p></div><div class="icon"><i class="fas fa-user-check"></i></div></div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="small-box bg-danger"><div class="inner"><h3>{{ loginFallados ?? 0 }}</h3><p>Login fallados</p></div><div class="icon"><i class="fas fa-user-times"></i></div></div>
          </div>
          <div class="col-lg-4 col-12">
            <div class="small-box bg-info"><div class="inner"><h3>{{ cantidadVisitas ?? 0 }}</h3><p>Visitas registradas en el sitio</p></div><div class="icon"><i class="fas fa-chart-line"></i></div></div>
          </div>
        </div>

        <div class="row mt-4">
          <section class="col-lg-6 mb-4">
            <div class="card h-100">
              <div class="card-header"><h4 class="card-title mb-0">Pedidos por mes</h4></div>
              <div class="card-body">
                <canvas v-if="mes && mes.length" ref="graficoPedidosRef"></canvas>
                <p v-else class="text-muted">No hay datos de pedidos por mes.</p>
              </div>
            </div>
          </section>

          <section class="col-lg-6 mb-4">
            <div class="card h-100">
              <div class="card-header"><h4 class="card-title mb-0">Recursos más accedidos</h4></div>
              <div class="card-body">
                <canvas v-if="recursosMasAccedidos && recursosMasAccedidos.length" ref="graficoRecursosRef"></canvas>
                <p v-else class="text-muted">Todavía no existen visitas registradas.</p>
              </div>
            </div>
          </section>

          <section class="col-lg-6 mb-4">
            <div class="card h-100">
              <div class="card-header"><h4 class="card-title mb-0">Productos más vendidos</h4></div>
              <div class="card-body">
                <div v-if="productosTop && productosTop.length" class="table-responsive">
                  <table class="table table-striped table-sm">
                    <thead><tr><th>Producto</th><th>Unidades</th></tr></thead>
                    <tbody><tr v-for="producto in productosTop" :key="producto.producto"><td>{{ producto.producto }}</td><td>{{ producto.total_vendido }}</td></tr></tbody>
                  </table>
                </div>
                <p v-else class="text-muted">No existen productos vendidos todavía.</p>
              </div>
            </div>
          </section>

          <section class="col-lg-6 mb-4">
            <div class="card h-100">
              <div class="card-header"><h4 class="card-title mb-0">Bitácora de accesos recientes</h4></div>
              <div class="card-body">
                <div v-if="ultimosAccesos && ultimosAccesos.length" class="table-responsive">
                  <table class="table table-hover table-sm">
                    <thead><tr><th>Fecha</th><th>Usuario</th><th>Evento</th><th>Estado</th><th>IP</th></tr></thead>
                    <tbody>
                      <tr v-for="acceso in ultimosAccesos" :key="`${acceso.fecha}-${acceso.email}-${acceso.evento}`">
                        <td>{{ acceso.fecha }}</td>
                        <td>{{ acceso.usuario }}</td>
                        <td>{{ acceso.evento }}</td>
                        <td>{{ acceso.estado }}</td>
                        <td>{{ acceso.ip }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <p v-else class="text-muted">Aún no hay registros de login en la bitácora.</p>
              </div>
            </div>
          </section>

          <section class="col-lg-12 mb-4">
            <div class="card">
              <div class="card-header"><h4 class="card-title mb-0">Alerta de stock mínimo</h4></div>
              <div class="card-body">
                <div v-if="stockBajo && stockBajo.length" class="table-responsive">
                  <table class="table table-hover table-sm">
                    <thead><tr><th>Producto</th><th>Stock actual</th><th>Stock mínimo</th></tr></thead>
                    <tbody><tr v-for="item in stockBajo" :key="item.nombre"><td>{{ item.nombre }}</td><td>{{ item.stock_actual }}</td><td>{{ item.stock_minimo }}</td></tr></tbody>
                  </table>
                </div>
                <p v-else class="text-muted">No hay productos por debajo del stock mínimo.</p>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
    <NoPermiso v-else mensaje="No tienes permisos para ver los reportes." />
  </AppLayout>
</template>
