<script setup lang="ts">
import { ref } from "vue";
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthLayout from "../../Layouts/AuthLayout.vue";

const activeTab = ref<"login" | "register">("login");
const isLoading = ref(false);

const loginForm = useForm({
    email: "",
    password: ""
});

const registerForm = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: ""
});

const handleLogin = () => {
    isLoading.value = true;
    loginForm.errors = {};
    setTimeout(() => {
        isLoading.value = false;
        loginForm.post(route('login'), {
            onFinish: () => loginForm.reset('password'),
        });
    }, 800);
};

const handleRegister = () => {
    isLoading.value = true;
    setTimeout(() => {
        registerForm.post(route('register'), {
            onFinish: () => registerForm.reset('password'),
        });
        isLoading.value = false;
        // activeTab.value = "login";
    }, 1000);
};
</script>

<template>

    <Head title="Iniciar Sesión" />
    <AuthLayout>
        <div class="login-section d-flex justify-content-center align-items-center">
            <div class="card login-card p-4" style="width: 400px;">

                <div class="text-center mb-2">
                    <a class="navbar-brand" style="text-decoration: none; color: #ffc107;" href="#"><i
                            class="fa fa-truck text-warning"></i>
                        DISTRIBUIDORA CARLA</a>
                </div>
                <!-- Header -->
                <div class="login-header mb-3">
                    <h4 class="fw-bold">
                        {{ activeTab === 'login' ? 'Iniciar Sesión' : 'Crear Cuenta Nueva' }}</h4>
                </div>

                <!-- LOGIN FORM -->
                <form v-if="activeTab === 'login'" @submit.prevent="handleLogin">

                    <div v-if="loginForm.errors" class="text-danger text-sm mb-2 text-center">{{ loginForm.errors.email
                        }}
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Correo Electrónico</label>
                        <input type="email" class="form-control" v-model="loginForm.email"
                            placeholder="nombre@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Contraseña</label>
                        <input type="password" class="form-control" v-model="loginForm.password" required>
                        <div v-if="loginForm.errors.password" class="text-danger text-sm">{{ loginForm.errors.password
                            }}</div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 my-4" :disabled="isLoading">
                        {{ isLoading ? 'Entrando...' : 'Entrar' }}
                    </button>

                    <!-- <div class="text-center">
                        <small class="text-muted">¿No tienes cuenta?
                            <a href="#" @click.prevent="activeTab = 'register'" class="text-warning fw-bold">Regístrate
                                aquí</a>
                        </small>
                    </div> -->
                </form>

                <!-- REGISTER FORM -->
                <form v-else @submit.prevent="handleRegister">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre Completo</label>
                        <input type="text" class="form-control" v-model="registerForm.name" placeholder="Tu nombre"
                            required>
                        <div v-if="registerForm.errors.name" class="text-danger text-sm">{{ registerForm.errors.name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Correo Electrónico</label>
                        <input type="email" class="form-control" v-model="registerForm.email"
                            placeholder="nombre@ejemplo.com" required>
                        <div v-if="registerForm.errors.email" class="text-danger text-sm">{{ registerForm.errors.email
                            }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Contraseña</label>
                        <input type="password" class="form-control" v-model="registerForm.password" required>
                        <div v-if="registerForm.errors.password" class="text-danger text-sm">{{
                            registerForm.errors.password }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Confirmar Contraseña</label>
                        <input type="password" class="form-control" v-model="registerForm.password_confirmation"
                            required>
                        <div v-if="registerForm.errors.password_confirmation" class="text-danger text-sm">{{
                            registerForm.errors.password_confirmation }}
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 my-4" :disabled="isLoading">
                        {{ isLoading ? 'Registrando...' : 'Crear Cuenta' }}
                    </button>

                    <div class="text-center">
                        <small class="text-muted">¿Ya eres cliente?
                            <a href="#" @click.prevent="activeTab = 'login'" class="text-warning fw-bold">Inicia sesión</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>


<style>
/* --- Incluye tus estilos proporcionados --- */
:root {
    --primary-color: #00bcd4;
    --primary-dark: #008ba3;
    --secondary-color: #1a252f;
    --light-bg: #f8f9fa;
    --text-muted: #6c757d;
}

body {
    font-family: 'Open Sans', sans-serif;
    color: var(--secondary-color);
    overflow-x: hidden;
}

h1,
h2,
h3,
h4,
h5,
h6,
.navbar-brand {
    font-family: 'Montserrat', sans-serif;
    letter-spacing: -0.5px;
}

.text-warning {
    color: #ffc107;
}

.bg-cyan {
    background-color: #ffc107;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    border-radius: 30px;
    padding: 10px 30px;
    transition: all 0.3s;
    font-weight: 600;
}

.btn-warning:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-outline-light {
    border-radius: 30px;
}

.login-section {
    background-color: var(--light-bg);
    padding: 80px 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.login-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    color: var(--secondary-color);
}

.login-header {
    padding: 30px;
    text-align: center;
    transition: background-color 0.3s;
}
</style>
