
<div x-data="checkInModal"
     x-show="isOpen"
     @open-modal.window="handleOpen($event)"
     @keydown.escape.window="close"
     class="relative z-50">
    
    <!-- Fondo Oscuro -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                
                <!-- Encabezado -->
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                            Registrar Entrada
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Ingrese el DNI del empleado para registrar su entrada.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form @submit.prevent="handleSubmit" class="mt-5">
                    <div class="space-y-4">
                        <!-- Campo DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700">
                                DNI del Empleado
                            </label>
                            <input type="text" 
                                   id="dni" 
                                   x-model="formData.dni"
                                   required
                                   pattern="[0-9]{8}"
                                   maxlength="8"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   :class="{'border-red-300': errors.dni}"
                                   @input="validateDNI">
                            <p x-show="errors.dni" 
                               x-text="errors.dni" 
                               class="mt-2 text-sm text-red-600"></p>
                        </div>

                        <!-- Campo Ubicación -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">
                                Ubicación
                            </label>
                            <input type="text" 
                                   id="location" 
                                   x-model="formData.location"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button type="submit"
                                :disabled="loading || Object.keys(errors).length > 0"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 sm:col-start-2 disabled:opacity-50">
                            <span x-show="!loading">Registrar Entrada</span>
                            <svg x-show="loading" class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </button>
                        <button type="button"
                                @click="close"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkInModal', () => ({
        isOpen: false,
        loading: false,
        formData: {
            dni: '',
            location: ''
        },
        errors: {},

        handleOpen(event) {
            if (event.detail === 'check-in') {
                this.isOpen = true;
            }
        },

        close() {
            this.isOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.formData = {
                dni: '',
                location: ''
            };
            this.errors = {};
        },

        validateDNI() {
            this.errors = {};
            if (!/^[0-9]{8}$/.test(this.formData.dni)) {
                this.errors.dni = 'El DNI debe tener 8 dígitos';
            }
        },

        async handleSubmit() {
            if (this.loading) return;
            this.loading = true;

            try {
                const response = await fetch('/attendance/check-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                
                if (data.success) {
                    this.$dispatch('notify', {
                        type: 'success',
                        message: 'Entrada registrada exitosamente'
                    });
                    this.close();
                    window.location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message || 'Error al registrar entrada'
                });
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>