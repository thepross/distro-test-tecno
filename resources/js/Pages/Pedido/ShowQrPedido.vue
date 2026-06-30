<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  pedido: Object,
  qrImage: String,
  callbackUrl: String,
  montoPrueba: Number,
});

function formatMoney(value) {
  return new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(Number(value || 0));
}
</script>

<template>
  <Head title="QR Pedido" />
  <AppLayout title="Pago con QR">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
          <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">Pago con QR PagoFácil</h2>
            <p class="text-muted mb-1">Pedido #{{ pedido.id }}</p>
            <small class="text-muted">Callback: {{ callbackUrl }}</small>
          </div>

          <div class="card shadow border-0 rounded-3">
            <div class="card-body p-4 text-center">
              <div class="alert alert-info text-start">
                <b>Modo prueba:</b> el QR cobra {{ formatMoney(montoPrueba) }}. El monto real de {{ formatMoney(pedido.total) }} se envía en el detalle/motivo del QR.
              </div>

              <div class="mb-4 d-flex justify-content-center gap-2 flex-wrap">
                <span class="badge rounded-pill bg-primary px-3 py-2">Pedido {{ pedido.id }}</span>
                <span v-if="pedido.pagofacil_transaction_id" class="badge rounded-pill bg-info px-3 py-2">
                  ID: {{ pedido.pagofacil_transaction_id }}
                </span>
              </div>

              <div class="d-flex justify-content-center mb-4">
                <div v-if="qrImage" class="border rounded bg-white shadow-sm p-2">
                  <img :src="'data:image/png;base64,' + qrImage" alt="QR PagoFácil" class="img-fluid" style="max-width: 320px; height: auto;" />
                </div>
                <div v-else class="bg-light border rounded d-flex flex-column align-items-center justify-content-center" style="width: 300px; height: 300px;">
                  <div class="spinner-border text-secondary mb-2" role="status"></div>
                  <span class="small text-muted">Generando QR...</span>
                </div>
              </div>

              <div class="border-top pt-3 text-start">
                <div class="d-flex justify-content-between mb-1">
                  <span class="text-muted">Monto real:</span>
                  <b>{{ formatMoney(pedido.total) }}</b>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted">Monto QR prueba:</span>
                  <b>{{ formatMoney(montoPrueba) }}</b>
                </div>
              </div>

              <Link :href="route('pedido.index')" class="btn btn-primary w-100 mt-4">
                Volver a pedidos
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
