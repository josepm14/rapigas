
<?php require_once '../layouts/app.php'; ?>

<div x-data="employeeDetails" class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="/employees" class="text-indigo-600 hover:text-indigo-900 mr-4">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Detalles del Empleado</h1>
                </div>
                <div class="flex space-x-3">
                    <button @click="window.location.href=`/employees/${employee.id}/edit`"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                    <button @click="deleteEmployee"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Loading State -->
                <div x-show="loading" class="flex justify-center items-center p-12">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </div>

                <!-- Employee Information -->
                <div x-show="!loading">
                    <!-- Profile Header -->
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20">
                                <img :src="employee.avatar || '/img/default-avatar.png'"
                                     :alt="employee.name"
                                     class="h-20 w-20 rounded-full">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900" x-text="employee.name"></h3>
                                <p class="text-sm text-gray-500" x-text="employee.position"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
                        <div class="mt-4 border-t border-gray-200">
                            <dl class="divide-y divide-gray-200">
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">DNI</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="employee.dni"></dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="employee.email"></dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="employee.phone"></dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Departamento</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="employee.department"></dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="employee.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                            <span x-text="employee.status === 'active' ? 'Activo' : 'Inactivo'"></span>
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Attendance Statistics -->
                    <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas de Asistencia</h3>
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                            <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Asistencias (Este Mes)</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-indigo-600" x-text="stats.attendances"></dd>
                                </div>
                            </div>
                            <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Tardanzas</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-yellow-600" x-text="stats.lates"></dd>
                                </div>
                            </div>
                            <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Ausencias</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-red-600" x-text="stats.absences"></dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actividad Reciente</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                <template x-for="(activity, index) in recentActivity" :key="activity.id">
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" 
                                                  x-show="index !== recentActivity.length - 1"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span :class="getActivityIconClass(activity.type)"
                                                          class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" 
                                                             x-html="getActivityIcon(activity.type)"></svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500" x-text="activity.description"></p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time x-text="formatDate(activity.timestamp)"></time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeDetails', () => ({
        loading: true,
        employee: {},
        stats: {
            attendances: 0,
            lates: 0,
            absences: 0
        },
        recentActivity: [],

        init() {
            const id = new URLSearchParams(window.location.search).get('id');
            this.loadEmployee(id);
        },

        async loadEmployee(id) {
            try {
                const response = await fetch(`/employees/${id}`);
                const data = await response.json();
                
                if (data.success) {
                    this.employee = data.employee;
                    this.stats = data.stats;
                    this.recentActivity = data.recentActivity;
                } else {
                    throw new Error(data.message);
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

        async deleteEmployee() {
            if (!confirm('¿Está seguro de eliminar este empleado?')) return;

            try {
                const response = await fetch(`/employees/${this.employee.id}`, {
                    method: 'DELETE'
                });
                const data = await response.json();

                if (data.success) {
                    this.$dispatch('notify', {
                        type: 'success',
                        message: 'Empleado eliminado exitosamente'
                    });
                    window.location.href = '/employees';
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message || 'Error al eliminar empleado'
                });
            }
        },

        getActivityIconClass(type) {
            return {
                'check-in': 'bg-green-500',
                'check-out': 'bg-blue-500',
                'late': 'bg-yellow-500',
                'absent': 'bg-red-500'
            }[type] || 'bg-gray-500';
        },

        getActivityIcon(type) {
            const icons = {
                'check-in': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />',
                'check-out': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />',
                'late': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'absent': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
            };
            return icons[type] || icons['absent'];
        },

        formatDate(timestamp) {
            return new Date(timestamp).toLocaleString('es-PE', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }));
});
</script>