<?php require_once '../layouts/auth.php'; ?>

<div x-data="recoveryForm" class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Encabezado -->
        <div>
            <img class="mx-auto h-16 w-auto" src="/img/logo.png" alt="Rapigas">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Recuperar Contraseña
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ingresa tu correo electrónico y te enviaremos las instrucciones
            </p>
        </div>

        <!-- Formulario -->
        <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
            <!-- Alertas -->
            <div x-show="message" 
                 :class="{'bg-green-50': success, 'bg-red-50': !success}"
                 class="rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg x-show="success" class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg x-show="!success" class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" 
                           :class="{'text-green-800': success, 'text-red-800': !success}"
                           x-text="message"></p>
                    </div>
                </div>
            </div>

            <!-- Campo Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Correo Electrónico
                </label>
                <div class="mt-1">
                    <input id="email" 
                           name="email" 
                           type="email" 
                           required
                           x-model="formData.email"
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="ejemplo@empresa.com">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between">
                <a href="/auth/login" 
                   class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Volver al inicio de sesión
                </a>
                <button type="submit"
                        :disabled="loading"
                        class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <svg x-show="loading" 
                         class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                         fill="none" 
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span x-show="!loading">Enviar Instrucciones</span>
                    <span x-show="loading">Enviando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('recoveryForm', () => ({
        loading: false,
        success: false,
        message: null,
        formData: {
            email: ''
        },

        async handleSubmit() {
            this.loading = true;
            this.message = null;

            try {
                const response = await fetch('/auth/recovery', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                this.success = data.success;
                this.message = data.message;

                if (data.success) {
                    this.formData.email = '';
                }
            } catch (error) {
                this.success = false;
                this.message = 'Error de conexión. Intente nuevamente.';
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>