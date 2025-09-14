<div x-data="employeeStats" class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <!-- Título y Filtros -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900">
                Estadísticas de Personal
            </h3>
            <select x-model="selectedDepartment" 
                    @change="loadStats()"
                    class="mt-1 block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">Todos los departamentos</option>
                <template x-for="dept in departments" :key="dept.id">
                    <option :value="dept.id" x-text="dept.name"></option>
                </template>
            </select>
        </div>

        <!-- Estado de Carga -->
        <div x-show="loading" class="flex justify-center py-8">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Contenido Principal -->
        <div x-show="!loading" class="space-y-6">
            <!-- Métricas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500">Tasa de Asistencia</dt>
                    <dd class="mt-1 flex justify-between items-baseline">
                        <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                            <span x-text="stats.attendanceRate + '%'"></span>
                        </div>
                        <div :class="stats.attendanceRateTrend > 0 ? 'text-green-600' : 'text-red-600'"
                             class="inline-flex items-baseline text-sm font-semibold">
                            <template x-if="stats.attendanceRateTrend > 0">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="stats.attendanceRateTrend < 0">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <span x-text="Math.abs(stats.attendanceRateTrend) + '%'"></span>
                        </div>
                    </dd>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500">Promedio Tardanzas</dt>
                    <dd class="mt-1 text-2xl font-semibold text-indigo-600" x-text="stats.avgLates + ' min'"></dd>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500">Personal Activo</dt>
                    <dd class="mt-1 text-2xl font-semibold text-indigo-600" x-text="stats.activeEmployees"></dd>
                </div>
            </div>

            <!-- Tabla de Top Empleados -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-500 mb-3">Top Empleados por Asistencia</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Empleado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Departamento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Asistencia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Puntualidad
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="employee in topEmployees" :key="employee.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900" x-text="employee.name"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500" x-text="employee.department"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-indigo-600 h-2.5 rounded-full" 
                                                     :style="`width: ${employee.attendance_rate}%`"></div>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-600" 
                                                  x-text="employee.attendance_rate + '%'"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="getPunctualityClass(employee.punctuality)">
                                            <span x-text="employee.punctuality + '%'"></span>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeStats', () => ({
        loading: true,
        selectedDepartment: '',
        departments: [],
        stats: {
            attendanceRate: 0,
            attendanceRateTrend: 0,
            avgLates: 0,
            activeEmployees: 0
        },
        topEmployees: [],

        init() {
            this.loadDepartments();
            this.loadStats();
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

        async loadStats() {
            this.loading = true;
            try {
                const response = await fetch(`/dashboard/employee-stats?department=${this.selectedDepartment}`);
                const data = await response.json();
                if (data.success) {
                    this.stats = data.stats;
                    this.topEmployees = data.topEmployees;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al cargar las estadísticas'
                });
            } finally {
                this.loading = false;
            }
        },

        getPunctualityClass(punctuality) {
            if (punctuality >= 90) return 'bg-green-100 text-green-800';
            if (punctuality >= 70) return 'bg-yellow-100 text-yellow-800';
            return 'bg-red-100 text-red-800';
        }
    }));
});
</script>