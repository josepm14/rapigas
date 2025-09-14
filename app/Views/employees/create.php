<?php require_once '../layouts/app.php'; ?>

<div x-data="employeeCreate" class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <a href="/employees" class="text-indigo-600 hover:text-indigo-900 mr-4">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Nuevo Empleado</h1>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Form Container -->
            <form @submit.prevent="createEmployee" class="space-y-6">
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <!-- Form Description -->
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Información Personal
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Complete los datos del nuevo empleado.
                            </p>
                        </div>

                        <!-- Form Fields -->
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- Nombre -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        Nombre Completo
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           x-model="form.name"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <div x-show="errors.name" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.name"></div>
                                </div>

                                <!-- DNI -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="dni" class="block text-sm font-medium text-gray-700">
                                        DNI
                                    </label>
                                    <input type="text" 
                                           id="dni" 
                                           x-model="form.dni"
                                           maxlength="8"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <div x-show="errors.dni" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.dni"></div>
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           x-model="form.email"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <div x-show="errors.email" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.email"></div>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">
                                        Teléfono
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           x-model="form.phone"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <div x-show="errors.phone" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.phone"></div>
                                </div>

                                <!-- Departamento -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="department" class="block text-sm font-medium text-gray-700">
                                        Departamento
                                    </label>
                                    <select id="department" 
                                            x-model="form.department_id"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccione...</option>
                                        <template x-for="dept in departments" :key="dept.id">
                                            <option :value="dept.id" x-text="dept.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="errors.department_id" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.department_id"></div>
                                </div>

                                <!-- Contraseña -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        Contraseña
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           x-model="form.password"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <div x-show="errors.password" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.password"></div>
                                </div>

                                <!-- Confirmar Contraseña -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                        Confirmar Contraseña
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           x-model="form.password_confirmation"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <button type="button"
                            @click="window.location.href='/employees'"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            :disabled="saving"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg x-show="saving" 
                             class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                             fill="none" 
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span x-text="saving ? 'Guardando...' : 'Crear Empleado'"></span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeCreate', () => ({
        saving: false,
        departments: [],
        form: {
            name: '',
            dni: '',
            email: '',
            phone: '',
            department_id: '',
            password: '',
            password_confirmation: ''
        },
        errors: {},

        init() {
            this.loadDepartments();
        },

        async loadDepartments() {
            try {
                const response = await fetch('/departments');
                const data = await response.json();
                if (data.success) {
                    this.departments = data.departments;
                }
            } catch (error) {
                console.error('Error loading departments:', error);
            }
        },

        async createEmployee() {
            if (this.saving) return;
            this.saving = true;
            this.errors = {};

            try {
                const response = await fetch('/employees', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    this.$dispatch('notify', {
                        type: 'success',
                        message: 'Empleado creado exitosamente'
                    });
                    window.location.href = '/employees';
                } else {
                    this.errors = data.errors || {};
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message || 'Error al crear empleado'
                });
            } finally {
                this.saving = false;
            }
        }
    }));
});
</script>