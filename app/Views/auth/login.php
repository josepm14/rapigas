
<?php require_once '../layouts/auth.php'; ?>

<div x-data="loginForm" class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo y Título -->
        <div>
            <img class="mx-auto h-16 w-auto" src="/img/logo.png" alt="Rapigas">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Iniciar Sesión
            </h2>
        </div>

        <!-- Formulario -->
        <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
            <!-- Alertas de Error -->
            <div x-show="error" 
                 class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                    </div>
                </div>
            </div>

            <div class="rounded-md shadow-sm -space-y-px">
                <!-- Usuario -->
                <div>
                    <label for="username" class="sr-only">Usuario</label>
                    <input id="username" 
                           name="username" 
                           type="text" 
                           required 
                           x-model="formData.username"
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Usuario">
                </div>
                <!-- Contraseña -->
                <div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           required 
                           x-model="formData.password"
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Contraseña">
                </div>
            </div>

            <!-- Recordarme y Recuperar Contraseña -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" 
                           name="remember_me" 
                           type="checkbox"
                           x-model="formData.remember"
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="/auth/recovery" class="font-medium text-indigo-600 hover:text-indigo-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <!-- Botón Submit -->
            <div>
                <button type="submit"
                        :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" 
                             x-show="!loading"
                             fill="currentColor" 
                             viewBox="0 0 20 20">
                            <path fill-rule="evenodd" 
                                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" 
                                  clip-rule="evenodd" />
                        </svg>
                        <svg x-show="loading" 
                             class="animate-spin h-5 w-5 text-white" 
                             fill="none" 
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" 
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </span>
                    <span x-show="!loading">Iniciar Sesión</span>
                    <span x-show="loading">Procesando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('loginForm', () => ({
        loading: false,
        error: null,
        formData: {
            username: '',
            password: '',
            remember: false
        },

        async handleSubmit() {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch('/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '/dashboard';
                } else {
                    this.error = data.message || 'Error al iniciar sesión';
                }
            } catch (error) {
                this.error = 'Error de conexión. Intente nuevamente.';
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>