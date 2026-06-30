<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const categorias = [
  { title: 'Abarrotes', desc: 'Productos de alta rotación para tiendas y mercados.', icon: 'fa fa-shopping-basket' },
  { title: 'Limpieza', desc: 'Artículos para el hogar, negocios y consumo diario.', icon: 'fa fa-spray-can' },
  { title: 'Snacks y bebidas', desc: 'Variedad para puntos de venta minoristas.', icon: 'fa fa-box-open' },
];

const procesos = [
  'Gestión de productos, compras e inventario en tiempo real.',
  'Registro de pedidos, detalle de pedido y control de entregas.',
  'Pagos al contado, planes de pago y seguimiento de cuotas.',
];
</script>

<template>
  <Head title="Distribuidora Carla" />
  <AuthLayout>
    <div class="landing-carla">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container">
          <a class="navbar-brand fw-bold" href="#inicio"><i class="fa fa-truck text-warning me-2"></i>Distribuidora Carla</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
              <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
              <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
              <li class="nav-item"><a class="nav-link" href="#gestion">Gestión</a></li>
              <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
              <li class="nav-item ms-lg-3">
                <Link :href="route('login')" class="btn btn-warning text-dark fw-bold px-4">Ingresar al sistema</Link>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <header id="inicio" class="hero-section">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-7">
              <span class="badge bg-warning text-dark mb-3 px-3 py-2">Distribución mayorista y minorista</span>
              <h1 class="display-4 fw-bold mb-4">Productos disponibles, pedidos ordenados y entregas controladas</h1>
              <p class="lead mb-4 text-white-75">
                Sistema web para administrar la operación integral de Distribuidora Carla: compras, inventario, pedidos, entregas, pagos, planes de pago y reportes.
              </p>
              <div class="d-flex gap-3 flex-wrap">
                <Link :href="route('login')" class="btn btn-warning btn-lg text-dark fw-bold px-5">Acceder</Link>
                <a href="#gestion" class="btn btn-outline-light btn-lg px-5">Ver módulos</a>
              </div>
            </div>
            <div class="col-lg-5 mt-5 mt-lg-0">
              <div class="hero-card">
                <h4 class="fw-bold mb-3">Panel operativo</h4>
                <div class="metric"><span>Productos</span><strong>Stock y precios</strong></div>
                <div class="metric"><span>Pedidos</span><strong>Ventas y entregas</strong></div>
                <div class="metric"><span>Pagos</span><strong>Cuotas y saldos</strong></div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <section id="productos" class="section-padding bg-light">
        <div class="container">
          <div class="text-center mb-5">
            <h6 class="text-warning fw-bold text-uppercase">Catálogo comercial</h6>
            <h2 class="fw-bold">Líneas de productos para distribución</h2>
            <p class="text-muted">Organiza categorías, marcas, unidades de medida, precios y stock mínimo.</p>
          </div>
          <div class="row g-4">
            <div class="col-md-4" v-for="(cat, index) in categorias" :key="index">
              <div class="card h-100 category-card p-4 text-center">
                <div class="category-icon"><i :class="cat.icon"></i></div>
                <h4 class="fw-bold">{{ cat.title }}</h4>
                <p class="text-muted mb-0">{{ cat.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="gestion" class="section-padding">
        <div class="container">
          <div class="row align-items-center g-5">
            <div class="col-lg-6">
              <h6 class="text-warning fw-bold text-uppercase">Gestión centralizada</h6>
              <h2 class="fw-bold mb-4">Estructura adaptada al flujo real de una distribuidora</h2>
              <ul class="list-unstyled">
                <li v-for="item in procesos" :key="item" class="process-item">
                  <i class="fa fa-check-circle text-warning me-2"></i>{{ item }}
                </li>
              </ul>
            </div>
            <div class="col-lg-6">
              <div class="modules-box">
                <div class="module-pill">Roles</div>
                <div class="module-pill">Usuarios</div>
                <div class="module-pill">Privilegios</div>
                <div class="module-pill">Productos</div>
                <div class="module-pill">Compras</div>
                <div class="module-pill">Inventario</div>
                <div class="module-pill">Pedidos</div>
                <div class="module-pill">Entregas</div>
                <div class="module-pill">Pagos</div>
                <div class="module-pill">Plan de pago</div>
                <div class="module-pill">Cuotas</div>
                <div class="module-pill">Reportes</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <footer id="contacto" class="bg-dark text-white py-5">
        <div class="container">
          <div class="row g-4">
            <div class="col-md-5">
              <h4 class="fw-bold mb-3"><i class="fa fa-truck text-warning me-2"></i>Distribuidora Carla</h4>
              <p class="text-white-50">Soluciones de abastecimiento para tiendas, mercados y negocios.</p>
            </div>
            <div class="col-md-4">
              <h5 class="mb-3">Contacto</h5>
              <p class="text-white-50 mb-1"><i class="fa fa-map-marker-alt me-2"></i>Av. Principal N° 123</p>
              <p class="text-white-50 mb-1"><i class="fa fa-phone me-2"></i>70000000</p>
              <p class="text-white-50"><i class="fa fa-envelope me-2"></i>contacto@distribuidoracarla.com</p>
            </div>
            <div class="col-md-3">
              <h5 class="mb-3">Horario</h5>
              <p class="text-white-50 mb-1">Lun - Sáb</p>
              <p class="text-white-50">08:00 a 18:00</p>
            </div>
          </div>
          <hr class="border-secondary mt-4">
          <div class="text-center text-white-50 small">&copy; 2026 Distribuidora Carla. Todos los derechos reservados.</div>
        </div>
      </footer>
    </div>
  </AuthLayout>
</template>

<style>
:root {
  --carla-dark: #19222d;
  --carla-yellow: #ffc107;
  --carla-muted: #6c757d;
}
.landing-carla {
  font-family: 'Source Sans Pro', Arial, sans-serif;
  color: var(--carla-dark);
  overflow-x: hidden;
}
.navbar { background: rgba(255,255,255,.96); }
.navbar-brand { letter-spacing: .3px; }
.hero-section {
  min-height: 88vh;
  padding-top: 95px;
  display: flex;
  align-items: center;
  color: white;
  background:
    linear-gradient(110deg, rgba(25,34,45,.95), rgba(25,34,45,.70)),
    url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1800&q=80');
  background-size: cover;
  background-position: center;
}
.text-white-75 { color: rgba(255,255,255,.78); }
.hero-card {
  background: rgba(255,255,255,.96);
  color: var(--carla-dark);
  border-radius: 22px;
  padding: 28px;
  box-shadow: 0 20px 50px rgba(0,0,0,.25);
}
.metric {
  display: flex;
  justify-content: space-between;
  gap: 16px;
  padding: 16px 0;
  border-bottom: 1px solid #ececec;
}
.metric:last-child { border-bottom: none; }
.section-padding { padding: 85px 0; }
.category-card {
  border: none;
  border-radius: 18px;
  box-shadow: 0 10px 25px rgba(0,0,0,.06);
  transition: all .25s ease;
}
.category-card:hover { transform: translateY(-6px); box-shadow: 0 20px 35px rgba(0,0,0,.10); }
.category-icon {
  width: 72px;
  height: 72px;
  border-radius: 18px;
  margin: 0 auto 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  background: rgba(255,193,7,.18);
  color: #c99700;
}
.process-item {
  font-size: 1.05rem;
  margin-bottom: 14px;
  padding: 12px 14px;
  border-left: 4px solid var(--carla-yellow);
  background: #fff9e6;
  border-radius: 8px;
}
.modules-box {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 14px;
  padding: 24px;
  border-radius: 22px;
  background: #f8f9fa;
}
.module-pill {
  background: white;
  border-radius: 50px;
  padding: 12px 16px;
  font-weight: 700;
  text-align: center;
  box-shadow: 0 8px 20px rgba(0,0,0,.05);
}
@media (max-width: 768px) {
  .hero-section { min-height: auto; padding: 120px 0 70px; }
  .modules-box { grid-template-columns: 1fr; }
}
</style>
