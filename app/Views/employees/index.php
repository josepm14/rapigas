<?php require_once '../layouts/app.php'; ?>

<div x-data="employeesList" class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Empleados</h1>
                <button @click="window.location.href='/employees/create'" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Empleado
                </button>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-4">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" 
                                   x-model="filters.search"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nombre o DNI">
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Departamento</label>
                            <select x-model="filters.department"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <template x-for="dept in departments" :key="dept.id">
                                    <option :value="dept.id" x-text="dept.name"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select x-model="filters.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                    :disabled="loading"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg x-show="!loading" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <svg x-show="loading" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                Filtrar
                            </button>
                            <button type="button"
                                    @click="resetFilters"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Employee List -->
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Empleado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Departamento
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Último Ingreso
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="employee in employees" :key="employee.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" 
                                                     :src="employee.avatar || '/img/default-avatar.png'"
                                                     :alt="employee.name">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900" x-text="employee.name"></div>
                                                <div class="text-sm text-gray-500" x-text="employee.dni"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="employee.department"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="employee.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                            <span x-text="employee.status === 'active' ? 'Activo' : 'Inactivo'"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(employee.last_login)">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="viewEmployee(employee.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</button>
                                        <button @click="editEmployee(employee.id)"
                                                class="text-green-600 hover:text-green-900 mr-3">Editar</button>
                                        <button @click="deleteEmployee(employee.id)"
                                                class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button @click="prevPage"
                                    :disabled="currentPage === 1"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Anterior
                            </button>
                            <button @click="nextPage"
                                    :disabled="currentPage === totalPages"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Siguiente
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Mostrando
                                    <span class="font-medium" x-text="pageStart"></span>
                                    a
                                    <span class="font-medium" x-text="pageEnd"></span>
                                    de
                                    <span class="font-medium" x-text="totalEmployees"></span>
                                    resultados
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <button @click="prevPage"
                                            :disabled="currentPage === 1"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Anterior
                                    </button>
                                    <template x-for="page in pages" :key="page">
                                        <button @click="goToPage(page)"
                                                :class="{'bg-indigo-50 border-indigo-500 text-indigo-600': page === currentPage}"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            <span x-text="page"></span>
                                        </button>
                                    </template>
                                    <button @click="nextPage"
                                            :disabled="currentPage === totalPages"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Siguiente
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeesList', () => ({
        loading: false,
        employees: [],
        departments: [],
        filters: {
            search: '',
            department: '',
            status: ''
        },
        pagination: {
            currentPage: 1,
            perPage: 10,
            total: 0
        },

        async init() {
            await this.loadDepartments();
            await this.loadEmployees();
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

        async loadEmployees() {
            this.loading = true;
            try {
                const queryParams = new URLSearchParams({
                    ...this.filters,
                    page: this.pagination.currentPage,
                    per_page: this.pagination.perPage
                });

                const response = await fetch(`/employees?${queryParams}`);
                const data = await response.json();
                
                if (data.success) {
                    this.employees = data.employees;
                    this.pagination = data.pagination;
                }
            } catch (error) {
                console.error('Error loading employees:', error);
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al cargar empleados'
                });
            } finally {
                this.loading = false;
            }
        },

        get pages() {
            const total = Math.ceil(this.pagination.total / this.pagination.perPage);
            return Array.from({ length: total }, (_, i) => i + 1);
        },

        get pageStart() {
            return ((this.pagination.currentPage - 1) * this.pagination.perPage) + 1;
        },

        get pageEnd() {
            return Math.min(this.pageStart + this.pagination.perPage - 1, this.pagination.total);
        },

        async applyFilters() {
            this.pagination.currentPage = 1;
            await this.loadEmployees();
        },

        resetFilters() {
            this.filters = {
                search: '',
                department: '',
                status: ''
            };
            this.applyFilters();
        },

        async goToPage(page) {
            this.pagination.currentPage = page;
            await this.loadEmployees();
        },

        prevPage() {
            if (this.pagination.currentPage > 1) {
                this.goToPage(this.pagination.currentPage - 1);
            }
        },

        nextPage() {
            if (this.pagination.currentPage < this.pages.length) {
                this.goToPage(this.pagination.currentPage + 1);
            }
        },

        formatDate(date) {
            return new Date(date).toLocaleString('es-PE', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        viewEmployee(id) {
            window.location.href = `/employees/${id}`;
        },

        editEmployee(id) {
            window.location.href = `/employees/${id}/edit`;
        },

        async deleteEmployee(id) {
            if (!confirm('¿Está seguro de eliminar este empleado?')) return;

            try {
                const response = await fetch(`/employees/${id}`, {
                    method: 'DELETE'
                });
                const data = await response.json();

                if (data.success) {
                    this.$dispatch('notify', {
                        type: 'success',
                        message: 'Empleado eliminado exitosamente'
                    });
                    await this.loadEmployees();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message || 'Error al eliminar empleado'
                });
            }
        }
    }));
});
</script>