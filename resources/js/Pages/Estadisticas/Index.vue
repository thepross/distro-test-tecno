<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  data: { type: Array, default: () => [] },
  buscar: { type: String, default: '' },
});
</script>

<template>
  <Head title="Resultados de búsqueda" />
  <AppLayout>
    <div class="card">
      <div class="card-header">
        <h1 class="card-title mb-0">
          <i class="fas fa-search mr-1"></i>
          <b>Resultado de la búsqueda</b>
        </h1>
      </div>
      <div class="card-body">
        <p class="mb-3">
          Texto buscado:
          <strong>{{ buscar || 'Sin filtro' }}</strong>
        </p>
        <div class="table-responsive">
          <table class="table table-hover table-sm" id="menus">
            <thead>
              <tr>
                <th>Módulo</th>
                <th>Coincidencia principal</th>
                <th>Detalle encontrado</th>
                <th>Acceso</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="d in data" :key="d.id">
                <td>{{ d.modelo }}</td>
                <td>{{ d.nombre }}</td>
                <td>{{ d.detalle }}</td>
                <td>
                  <Link :href="d.ruta" class="btn btn-sm btn-primary">
                    Ir al módulo
                  </Link>
                </td>
              </tr>
              <tr v-if="!data || data.length === 0">
                <td colspan="4" class="text-center">No se encontraron resultados.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
