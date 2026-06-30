<template>
    <!-- Auth Section (Login & Register) -->
    <section id="login" class="login-section">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Texto Informativo -->
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3">Acceso al Portal de Pacientes</h2>
                    <p class="text-muted mb-4">Gestiona tus citas, revisa tu historial de tratamientos y accede a
                        promociones exclusivas desde nuestro dashboard.</p>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-cyan me-2"></i> Agenda disponible 24/7
                        </li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-cyan me-2"></i> Historial clínico
                            digital</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-cyan me-2"></i> Control de pedidos y pagos
                        </li>
                    </ul>
                </div>

                <!-- Tarjeta de Autenticación -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="card login-card">
                        <div class="login-header">
                            <h4 class="m-0">
                                {{ authMode === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta Nueva' }}
                            </h4>
                        </div>
                        <div class="card-body p-4 p-lg-5">

                            <!-- FORMULARIO LOGIN -->
                            <form v-if="authMode === 'login'" @submit.prevent="handleLogin" key="login-form"
                                class="fade-in">
                                <div class="mb-3">
                                    <label for="email" class="form-label text-muted small">Correo Electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control border-start-0 bg-light"
                                            v-model="loginForm.email" placeholder="nombre@ejemplo.com" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label text-muted small">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control border-start-0 bg-light"
                                            v-model="loginForm.password" required>
                                    </div>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg" :disabled="isLoading">
                                        {{ isLoading ? 'Entrando...' : 'Entrar al Dashboard' }}
                                    </button>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted">¿No tienes cuenta? <a href="#"
                                            @click.prevent="authMode = 'register'" class="text-cyan fw-bold">Regístrate
                                            aquí</a></small>
                                </div>
                            </form>

                            <!-- FORMULARIO REGISTRO -->
                            <form v-else @submit.prevent="handleRegister" key="register-form" class="fade-in">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Nombre Completo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-person"></i></span>
                                        <input type="text" class="form-control border-start-0 bg-light"
                                            v-model="registerForm.name" placeholder="Tu nombre" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Correo Electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control border-start-0 bg-light"
                                            v-model="registerForm.email" placeholder="nombre@ejemplo.com" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Teléfono Móvil</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-phone"></i></span>
                                        <input type="tel" class="form-control border-start-0 bg-light"
                                            v-model="registerForm.phone" placeholder="+54 11..." required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control border-start-0 bg-light"
                                            v-model="registerForm.password" required>
                                    </div>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg" :disabled="isLoading">
                                        {{ isLoading ? 'Registrando...' : 'Crear Cuenta' }}
                                    </button>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted">¿Ya eres cliente? <a href="#"
                                            @click.prevent="authMode = 'login'" class="text-cyan fw-bold">Inicia
                                            sesión</a></small>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            currentView: 'landing',
            isLoading: false,

            loginForm: {
                email: 'demo@lumiere.com',
                password: '123'
            },

            services: [
                { title: 'Depilación Láser', desc: 'Eliminación definitiva del vello.', icon: 'bi bi-lightning-charge' },
                { title: 'Rejuvenecimiento', desc: 'Tratamientos faciales láser.', icon: 'bi bi-magic' },
                { title: 'Eliminación Tatuajes', desc: 'Tecnología Q-Switch avanzada.', icon: 'bi bi-droplet' }
            ]
        };
    },

    methods: {
        handleLogin(e) {
            e.preventDefault();
            this.isLoading = true;
            setTimeout(() => {
                this.currentView = 'dashboard';
                this.isLoading = false;
            }, 1200);
        }
    }
};
</script>