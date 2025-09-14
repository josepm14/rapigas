
<?php require_once '../layouts/auth.php'; ?>

<div x-data="resetPasswordForm" 
     class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Encabezado -->
        <div>
            <img class="mx-auto h-16 w-auto" src="/img/logo.png" alt="Rapigas">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Restablecer Contraseña
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ingresa tu nueva contraseña
            </p>
        </div>

        <!-- Formulario -->
        <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
            <input type="hidden" name="token" x-model="formData.token">

            <!-- Alertas -->
            <div x-show="message" 
                 :class="{'bg-green-50': success, 'bg-red-50': !success}"
                 class="rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg x-show="success" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <svg x-show="!success" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" 
                           :class="{'text-green-800': success, 'text-red-800': !success}"
                           x-text="message"></p>
                    </div>
                </div>
            </div>

            <!-- Nueva Contraseña -->
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Nueva Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password" 
                               name="password" 
                               type="password"
                               required
                               x-model="formData.password"
                               @input="validatePassword"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               :class="{'border-red-300': errors.password}">
                        <p x-show="errors.password" 
                           x-text="errors.password"
                           class="mt-2 text-sm text-red-600"></p>
                    </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmar Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password"
                               required
                               x-model="formData.password_confirmation"
                               @input="validatePasswordConfirmation"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               :class="{'border-red-300': errors.password_confirmation}">
                        <p x-show="errors.password_confirmation" 
                           x-text="errors.password_confirmation"
                           class="mt-2 text-sm text-red-600"></p>
                    </div>
                </div>
            </div>

            <!-- Botón Submit -->
            <div>
                <button type="submit"
                        :disabled="loading || hasErrors"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span x-show="!loading">Cambiar Contraseña</span>
                    <span x-show="loading">Procesando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resetPasswordForm', () => ({
        loading: false,
        success: false,
        message: null,
        formData: {
            token: new URLSearchParams(window.location.search).get('token'),
            password: '',
            password_confirmation: ''
        },
        errors: {},

        get hasErrors() {
            return Object.keys(this.errors).length > 0;
        },

        validatePassword() {
            this.errors = {};
            if (this.formData.password.length < 8) {
                this.errors.password = 'La contraseña debe tener al menos 8 caracteres';
            }
            this.validatePasswordConfirmation();
        },

        validatePasswordConfirmation() {
            if (this.formData.password !== this.formData.password_confirmation) {
                this.errors.password_confirmation = 'Las contraseñas no coinciden';
            }
        },

        async handleSubmit() {
            if (this.loading || this.hasErrors) return;
            this.loading = true;
            this.message = null;

            try {
                const response = await fetch('/auth/reset-password', {
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
                    setTimeout(() => {
                        window.location.href = '/auth/login';
                    }, 2000);
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