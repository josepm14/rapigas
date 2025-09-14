<?php require_once '../layouts/app.php'; ?>

<div x-data="employeeEdit" class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <a href="/employees" class="text-indigo-600 hover:text-indigo-900 mr-4">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Editar Empleado</h1>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div x-show="loading" class="flex justify-center items-center p-12">
                <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </div>

            <!-- Edit Form -->
            <form x-show="!loading" @submit.prevent="saveEmployee" class="space-y-6">
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Información Personal</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Datos básicos del empleado.
                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- Nombre -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                    <input type="text" 
                                           x-model="form.name"
                                           id="name"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div x-show="errors.name" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.name"></div>
                                </div>

                                <!-- DNI -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                                    <input type="text" 
                                           x-model="form.dni"
                                           id="dni"
                                           maxlength="8"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div x-show="errors.dni" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.dni"></div>
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" 
                                           x-model="form.email"
                                           id="email"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div x-show="errors.email" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.email"></div>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <input type="tel" 
                                           x-model="form.phone"
                                           id="phone"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div x-show="errors.phone" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.phone"></div>
                                </div>

                                <!-- Departamento -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="department" class="block text-sm font-medium text-gray-700">Departamento</label>
                                    <select x-model="form.department_id"
                                            id="department"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccione...</option>
                                        <template x-for="dept in departments" :key="dept.id">
                                            <option :value="dept.id" x-text="dept.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="errors.department_id" 
                                         class="mt-1 text-sm text-red-600" 
                                         x-text="errors.department_id"></div>
                                </div>

                                <!-- Estado -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                    <select x-model="form.status"
                                            id="status"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="active">Activo</option>
                                        <option value="inactive">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <button type="button"
                            @click="window.location.href='/employees'"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            :disabled="saving"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                        <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span x-text="saving ? 'Guardando...' : 'Guardar Cambios'"></span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeEdit', () => ({
        loading: true,
        saving: false,
        departments: [],
        form: {
            name: '',
            dni: '',
            email: '',
            phone: '',
            department_id: '',
            status: 'active'
        },
        errors: {},

        init() {
            this.loadDepartments();
            const id = new URLSearchParams(window.location.search).get('id');
            if (id) this.loadEmployee(id);
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

        async loadEmployee(id) {
            try {
                const response = await fetch(`/employees/${id}`);
                const data = await response.json();
                
                if (data.success) {
                    this.form = {
                        ...data.employee,
                        department_id: data.employee.department_id.toString()
                    };
                }
            } catch (error) {
                console.error('Error loading employee:', error);
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al cargar datos del empleado'
                });
            } finally {
                this.loading = false;
            }
        },

        async saveEmployee() {
            if (this.saving) return;
            this.saving = true;
            this.errors = {};

            try {
                const id = new URLSearchParams(window.location.search).get('id');
                const response = await fetch(`/employees/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    this.$dispatch('notify', {
                        type: 'success',
                        message: 'Empleado actualizado exitosamente'
                    });
                    window.location.href = `/employees/${id}`;
                } else {
                    this.errors = data.errors || {};
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message || 'Error al guardar cambios'
                });
            } finally {
                this.saving = false;
            }
        }
    }));
});
</script>