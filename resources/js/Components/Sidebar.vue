<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// Usamos ?. para evitar el crash si auth es null
const privileges = computed(() => page.props.auth?.privileges || [])

const hasPrivilege = (funcionalidad) => {
  return privileges.value.some(p => p.funcionalidad === funcionalidad && p.state === 'a')
}

const hasAnyPrivilege = (funcionalidades) => {
  return privileges.value.some(p => funcionalidades.includes(p.funcionalidad) && p.state === 'a')
}
</script>


<template>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Administracion -->
        <li class="nav-item" v-if="hasAnyPrivilege(['Usuario', 'Rol'])">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Administracion
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" v-if="hasPrivilege('Usuario')">
              <router-link :to="{ name: 'usuario.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Usuario</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Rol')">
              <router-link :to="{ name: 'rol.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Rol</p>
              </router-link>
            </li>
          </ul>
        </li>

        <!-- Gestion Clinica -->
        <li class="nav-item" v-if="hasAnyPrivilege(['Cliente', 'Cita', 'Venta', 'Pago', 'Servicio'])">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Gestion Clinica
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" v-if="hasPrivilege('Cliente')">
              <router-link :to="{ name: 'cliente.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Clientes</p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'cita.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Citas</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Servicio')">
              <router-link :to="{ name: 'servicio.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Servicios</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Pago')">
              <router-link :to="{ name: 'pago.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pagos</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Venta')">
              <router-link :to="{ name: 'venta.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Ventas</p>
              </router-link>
            </li>
          </ul>
        </li>

        <!-- Gestion de Recursos -->
        <li class="nav-item" v-if="hasAnyPrivilege(['Inventario', 'Producto', 'Categoria', 'Promocion'])">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Gestion de Recursos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" v-if="hasPrivilege('Inventario')">
              <router-link :to="{ name: 'inventario.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inventario</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Producto')">
              <router-link :to="{ name: 'producto.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Productos</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Categoria')">
              <router-link :to="{ name: 'categoria.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Categorias</p>
              </router-link>
            </li>
            <li class="nav-item" v-if="hasPrivilege('Promocion')">
              <router-link :to="{ name: 'promocion.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Promociones</p>
              </router-link>
            </li>
          </ul>
        </li>

        <!-- Reportes -->
        <li class="nav-item" v-if="hasPrivilege('Reportes')">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Reportes y estadisticas
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <router-link :to="{ name: 'estadisticas.index' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Estadisticas de acceso</p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'dashboard' }" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Reporte de ventas</p>
              </router-link>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>
</template>
