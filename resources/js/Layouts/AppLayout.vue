<script setup>
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch, nextTick } from 'vue';
import ControlSidebar from '../Components/ControlSidebar.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const num = computed(() => page.props.num || 0);
const flashSuccess = computed(() => page.props.flash?.success);
const errors = computed(() => page.props.errors || {});
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const userEstilo = computed(() => user.value ? user.value.estilo : null);
const openMenu = ref(null);
const fontSize = ref(localStorage.getItem('dc-font-size') || 'normal');
const highContrast = ref(localStorage.getItem('dc-high-contrast') === '1');

const can = (funcionalidad) => {
  return page.props.auth.privilegios?.[funcionalidad]?.leer;
};

const isActive = (path) => page.url === path || page.url.startsWith(path + '?');

const toggleMenu = (menu) => {
  openMenu.value = openMenu.value === menu ? null : menu;
};

const setInitialMenu = () => {
  const url = page.url || '';
  if (url.startsWith('/usuario') || url.startsWith('/rol')) {
    openMenu.value = 'administracion';
  } else if (url.startsWith('/producto') || url.startsWith('/compra') || url.startsWith('/inventario')) {
    openMenu.value = 'abastecimiento';
  } else if (url.startsWith('/pedido') || url.startsWith('/entrega') || url.startsWith('/pago') || url.startsWith('/planes') || url.startsWith('/cuota')) {
    openMenu.value = 'operaciones';
  } else if (url.startsWith('/reportes') || url.startsWith('/estadisticas') || url.startsWith('/dashboard')) {
    openMenu.value = 'reportes';
  }
};

const temaPorHorario = () => {
  const hora = new Date().getHours();
  return hora >= 6 && hora < 19 ? 2 : 4;
};

const tema = (estilo) => {
  const estiloReal = Number(estilo) === 5 ? temaPorHorario() : Number(estilo || 2);

  const temas = {
    1: {
      nombre: 'jovenes',
      bg: '#edf4ff', surface: '#ffffff', surface2: '#f7fbff', sidebar: '#1f3b73', sidebarText: '#f8fbff',
      text: '#18233f', muted: '#5f6f8f', border: '#c9d8f2', primary: '#2563eb', primaryText: '#ffffff', input: '#ffffff',
    },
    2: {
      nombre: 'adultos',
      bg: '#f4f6f9', surface: '#ffffff', surface2: '#fafafa', sidebar: '#263238', sidebarText: '#ffffff',
      text: '#1f2937', muted: '#6b7280', border: '#d6dce5', primary: '#0f766e', primaryText: '#ffffff', input: '#ffffff',
    },
    3: {
      nombre: 'ninos',
      bg: '#fff7da', surface: '#ffffff', surface2: '#fff1ba', sidebar: '#ff7a59', sidebarText: '#1f2937',
      text: '#263238', muted: '#5d4e3f', border: '#ffd166', primary: '#00a7b5', primaryText: '#ffffff', input: '#ffffff',
    },
    4: {
      nombre: 'noche',
      bg: '#111827', surface: '#1f2937', surface2: '#243244', sidebar: '#0b1220', sidebarText: '#f9fafb',
      text: '#f9fafb', muted: '#cbd5e1', border: '#374151', primary: '#60a5fa', primaryText: '#0b1220', input: '#111827',
    },
  };

  return temas[estiloReal] || temas[2];
};

const cambiarFondo = (estilo) => {
  const t = tema(estilo);
  document.body.className = `dc-theme-${t.nombre} hold-transition sidebar-mini layout-fixed`;

  const cssRules = `
    :root{
      --dc-bg:${t.bg};
      --dc-surface:${t.surface};
      --dc-surface-2:${t.surface2};
      --dc-sidebar:${t.sidebar};
      --dc-sidebar-text:${t.sidebarText};
      --dc-text:${t.text};
      --dc-muted:${t.muted};
      --dc-border:${t.border};
      --dc-primary:${t.primary};
      --dc-primary-text:${t.primaryText};
      --dc-input:${t.input};
    }
    body, .wrapper{background:var(--dc-bg)!important;color:var(--dc-text)!important;}
    .content-wrapper{background:var(--dc-bg)!important;color:var(--dc-text)!important;}
    .main-header{background:var(--dc-surface)!important;border-bottom:1px solid var(--dc-border)!important;color:var(--dc-text)!important;}
    .main-header .nav-link,.main-header a{color:var(--dc-text)!important;background:transparent!important;}
    .main-sidebar,.sidebar{background:var(--dc-sidebar)!important;color:var(--dc-sidebar-text)!important;}
    .brand-link{background:rgba(0,0,0,.08)!important;color:var(--dc-sidebar-text)!important;border-bottom:1px solid rgba(255,255,255,.12)!important;}
    .nav-sidebar .nav-link{color:var(--dc-sidebar-text)!important;border-radius:10px;margin:2px 8px;}
    .nav-sidebar .nav-link p,.nav-sidebar .nav-icon,.nav-sidebar .right{color:var(--dc-sidebar-text)!important;}
    .nav-sidebar .nav-link:hover,.nav-sidebar .menu-open>.nav-link,.nav-sidebar .nav-link.active{background:rgba(255,255,255,.18)!important;color:var(--dc-sidebar-text)!important;}
    .nav-treeview{padding-left:.25rem;}
    .nav-treeview .nav-link{background:rgba(255,255,255,.08)!important;margin-left:14px;}
    .card,.modal-content,.dropdown-menu{background:var(--dc-surface)!important;color:var(--dc-text)!important;border-color:var(--dc-border)!important;}
    .card-header,.modal-header,.modal-footer{background:var(--dc-surface-2)!important;color:var(--dc-text)!important;border-color:var(--dc-border)!important;}
    .card-body{background:var(--dc-surface)!important;color:var(--dc-text)!important;}
    .table,.table th,.table td,.table-hover tbody tr:hover{color:var(--dc-text)!important;border-color:var(--dc-border)!important;}
    .table thead th{background:var(--dc-surface-2)!important;color:var(--dc-text)!important;border-color:var(--dc-border)!important;}
    .table-striped tbody tr:nth-of-type(odd){background-color:rgba(127,127,127,.08)!important;}
    .form-control,.custom-select,input,select,textarea{background:var(--dc-input)!important;color:var(--dc-text)!important;border-color:var(--dc-border)!important;}
    .form-control::placeholder{color:var(--dc-muted)!important;}
    label,.text-muted,.help-block{color:var(--dc-muted)!important;}
    .btn-primary{background:var(--dc-primary)!important;border-color:var(--dc-primary)!important;color:var(--dc-primary-text)!important;}
    .pagination .page-link{background:var(--dc-surface)!important;color:var(--dc-text)!important;border-color:var(--dc-border)!important;}
    .pagination .active .page-link{background:var(--dc-primary)!important;color:var(--dc-primary-text)!important;}
    .main-footer{background:var(--dc-surface)!important;color:var(--dc-text)!important;border-top:1px solid var(--dc-border)!important;}
    .dropdown-item{color:var(--dc-text)!important;}
    .dropdown-item:hover{background:var(--dc-surface-2)!important;}
    .dc-high-contrast body,.dc-high-contrast .content-wrapper,.dc-high-contrast .card,.dc-high-contrast .card-body,.dc-high-contrast .main-header,.dc-high-contrast .main-footer{background:#000!important;color:#fff!important;}
    .dc-high-contrast .table,.dc-high-contrast .table th,.dc-high-contrast .table td,.dc-high-contrast label,.dc-high-contrast p,.dc-high-contrast h1,.dc-high-contrast h2,.dc-high-contrast h3,.dc-high-contrast h4,.dc-high-contrast h5{color:#fff!important;}
    .dc-high-contrast .form-control,.dc-high-contrast input,.dc-high-contrast select,.dc-high-contrast textarea{background:#000!important;color:#fff!important;border-color:#fff!important;}
    .dc-font-small{font-size:14px;}
    .dc-font-normal{font-size:16px;}
    .dc-font-large{font-size:18px;}
    .dc-font-large .table,.dc-font-large .form-control,.dc-font-large .btn{font-size:1.05rem;}
  `;

  let hoja = document.getElementById('dc-theme-style');
  if (!hoja) {
    hoja = document.createElement('style');
    hoja.id = 'dc-theme-style';
    document.head.appendChild(hoja);
  }
  hoja.innerHTML = cssRules;
};

const aplicarAccesibilidad = () => {
  document.documentElement.classList.remove('dc-font-small', 'dc-font-normal', 'dc-font-large', 'dc-high-contrast');
  document.documentElement.classList.add(`dc-font-${fontSize.value}`);
  if (highContrast.value) document.documentElement.classList.add('dc-high-contrast');
  localStorage.setItem('dc-font-size', fontSize.value);
  localStorage.setItem('dc-high-contrast', highContrast.value ? '1' : '0');
};

const cambiarTamanoLetra = (tamano) => {
  fontSize.value = tamano;
  aplicarAccesibilidad();
};

const alternarContraste = () => {
  highContrast.value = !highContrast.value;
  aplicarAccesibilidad();
};

watch(userEstilo, (nuevoEstilo) => {
  cambiarFondo(nuevoEstilo);
}, { immediate: true });

watch(() => page.url, () => setInitialMenu());

onMounted(() => {
  setInitialMenu();
  aplicarAccesibilidad();
  nextTick(() => {
    if (window.$) {
      window.$('[data-widget="pushmenu"]').PushMenu?.('init');
    }
  });
});

const form = useForm({
  buscar: ''
});

const realizarBusqueda = () => {
  form.get(route('reportes.buscar'), {
    preserveState: false,
    replace: true,
  });
};

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center" v-if="false">
      <img class="animation__shake" :src="`${page.props.assetUrl}/dist/img/AdminLTELogo.png`" alt="A" height="60" width="60">
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <Link :href="route('dashboard')" class="nav-link">Principal</Link>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Distribuidora</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-md-block mr-2">
          <form class="form-inline" @submit.prevent="realizarBusqueda">
            <div class="input-group input-group-sm">
              <input class="form-control" type="search" name="buscar" placeholder="Buscar productos, pedidos, pagos, usuarios..."
                v-model="form.buscar" aria-label="Buscar" :disabled="form.processing">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
            {{ user ? user.email : 'Invitado' }}
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <Link :href="route('profile.edit')" class="dropdown-item">Perfil</Link>
            <div class="dropdown-divider"></div>
            <button @click="logout" class="dropdown-item">Cerrar sesión</button>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Temas</a>
          <div class="dropdown-menu dropdown-menu-right">
            <Link :href="route('cargarEstilo', { estilo: '1' })" class="dropdown-item">Jóvenes</Link>
            <Link :href="route('cargarEstilo', { estilo: '2' })" class="dropdown-item">Adultos / Día</Link>
            <Link :href="route('cargarEstilo', { estilo: '3' })" class="dropdown-item">Niños</Link>
            <Link :href="route('cargarEstilo', { estilo: '4' })" class="dropdown-item">Noche</Link>
            <Link :href="route('cargarEstilo', { estilo: '5' })" class="dropdown-item">Automático día/noche</Link>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Accesibilidad</a>
          <div class="dropdown-menu dropdown-menu-right">
            <button class="dropdown-item" type="button" @click="cambiarTamanoLetra('small')">Letra pequeña</button>
            <button class="dropdown-item" type="button" @click="cambiarTamanoLetra('normal')">Letra normal</button>
            <button class="dropdown-item" type="button" @click="cambiarTamanoLetra('large')">Letra grande</button>
            <div class="dropdown-divider"></div>
            <button class="dropdown-item" type="button" @click="alternarContraste">
              {{ highContrast ? 'Desactivar alto contraste' : 'Activar alto contraste' }}
            </button>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <Link :href="route('dashboard')" class="brand-link" style="text-decoration: none;">
        <img :src="`${page.props.assetUrl}/dist/img/AdminLTELogo.png`" alt="DC" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span><b>Distribuidora Carla</b></span>
      </Link>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
            <li class="nav-item" :class="{ 'menu-is-opening menu-open': openMenu === 'administracion' }" v-if="can('Usuario') || can('Rol')">
              <a href="#" class="nav-link" @click.prevent="toggleMenu('administracion')">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Administración <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview" v-show="openMenu === 'administracion'">
                <li class="nav-item" v-if="can('Usuario')"><Link :href="route('usuario.index')" class="nav-link" :class="{ active: isActive('/usuario') }"><i class="far fa-circle nav-icon"></i><p>Usuarios</p></Link></li>
                <li class="nav-item" v-if="can('Rol')"><Link :href="route('rol.index')" class="nav-link" :class="{ active: isActive('/rol') }"><i class="far fa-circle nav-icon"></i><p>Roles</p></Link></li>
              </ul>
            </li>

            <li class="nav-item" :class="{ 'menu-is-opening menu-open': openMenu === 'abastecimiento' }" v-if="can('Producto') || can('Compra') || can('Inventario')">
              <a href="#" class="nav-link" @click.prevent="toggleMenu('abastecimiento')">
                <i class="nav-icon fas fa-warehouse"></i>
                <p>Abastecimiento <i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview" v-show="openMenu === 'abastecimiento'">
                <li class="nav-item" v-if="can('Producto')"><Link :href="route('producto.index')" class="nav-link" :class="{ active: isActive('/producto') }"><i class="far fa-circle nav-icon"></i><p>Productos</p></Link></li>
                <li class="nav-item" v-if="can('Compra')"><Link :href="route('compra.index')" class="nav-link" :class="{ active: isActive('/compra') }"><i class="far fa-circle nav-icon"></i><p>Compras</p></Link></li>
                <li class="nav-item" v-if="can('Inventario')"><Link :href="route('inventario.index')" class="nav-link" :class="{ active: isActive('/inventario') }"><i class="far fa-circle nav-icon"></i><p>Inventario</p></Link></li>
              </ul>
            </li>

            <li class="nav-item" :class="{ 'menu-is-opening menu-open': openMenu === 'operaciones' }" v-if="can('Pedido') || can('Entrega') || can('Pago') || can('PlanPago') || can('Cuota')">
              <a href="#" class="nav-link" @click.prevent="toggleMenu('operaciones')">
                <i class="nav-icon fas fa-truck-loading"></i>
                <p>Operaciones <i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview" v-show="openMenu === 'operaciones'">
                <li class="nav-item" v-if="can('Pedido')"><Link :href="route('pedido.index')" class="nav-link" :class="{ active: isActive('/pedido') }"><i class="far fa-circle nav-icon"></i><p>Pedidos</p></Link></li>
                <li class="nav-item" v-if="can('Entrega')"><Link :href="route('entrega.index')" class="nav-link" :class="{ active: isActive('/entrega') }"><i class="far fa-circle nav-icon"></i><p>Entregas</p></Link></li>
                <li class="nav-item" v-if="can('Pago')"><Link :href="route('pago.index')" class="nav-link" :class="{ active: isActive('/pago') }"><i class="far fa-circle nav-icon"></i><p>Pagos</p></Link></li>
                <li class="nav-item" v-if="can('PlanPago')"><Link :href="route('planes.index')" class="nav-link" :class="{ active: isActive('/planes') }"><i class="far fa-circle nav-icon"></i><p>Plan de pago</p></Link></li>
                <li class="nav-item" v-if="can('Cuota')"><Link :href="route('cuota.index')" class="nav-link" :class="{ active: isActive('/cuota') }"><i class="far fa-circle nav-icon"></i><p>Cuotas</p></Link></li>
              </ul>
            </li>

            <li class="nav-item" :class="{ 'menu-is-opening menu-open': openMenu === 'reportes' }" v-if="can('Reportes')">
              <a href="#" class="nav-link" @click.prevent="toggleMenu('reportes')">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>Reportes <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview" v-show="openMenu === 'reportes'">
                <li class="nav-item"><Link :href="route('reportes.index')" class="nav-link" :class="{ active: isActive('/reportes') || isActive('/dashboard') }"><i class="far fa-circle nav-icon"></i><p>Resumen general</p></Link></li>
                <li class="nav-item"><Link :href="route('estadisticas.index')" class="nav-link" :class="{ active: isActive('/estadisticas') }"><i class="far fa-circle nav-icon"></i><p>Estadísticas de acceso</p></Link></li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <div class="content-wrapper">
      <br>
      <section class="content">
        <div class="container-fluid">
          <div v-if="flashSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ flashSuccess }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>

          <div v-if="hasErrors" class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="icon fas fa-ban"></i> ¡Atención!</h5>
            <ul class="mb-0 pl-3"><li v-for="(error, key) in errors" :key="key">{{ error }}</li></ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>

          <div v-if="form.processing" class="loading-content">
            <i class="fas fa-circle-notch fa-spin fa-3x text-primary"></i>
            <h4 class="text-primary mt-3 font-weight-light">Buscando...</h4>
          </div>

          <div class="app"><slot /></div>
        </div>
      </section>
    </div>

    <footer class="main-footer">
      <strong>Copyright &copy; 2026 Distribuidora Carla.</strong>
      Todos los derechos reservados.
      <div class="float-right d-none d-sm-inline-block">Visitas: <strong>{{ num }}</strong></div>
    </footer>

    <ControlSidebar />
  </div>
</template>

<style scoped>
.loading-content { text-align: center; }
.nav-treeview { display: block; }
.nav-sidebar .menu-open > .nav-link .right { transform: rotate(-90deg); transition: transform .2s ease; }
</style>
