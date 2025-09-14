
<?php require_once '../layouts/app.php'; ?>

<div x-data="attendanceReport" class="min-h-screen bg-gray-50 py-6">
    <!-- Encabezado del Reporte -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Reporte de Asistencias
                </h1>
                <div class="flex space-x-3">
                    <button @click="exportReport('pdf')" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3C6.44772 3 6 3.44772 6 4V16C6 16.5523 6.44772 17 7 17H13C13.5523 17 14 16.5523 14 16V7.41421C14 7.149 13.8946 6.89464 13.7071 6.70711L10.2929 3.29289C10.1054 3.10536 9.851 3 9.58579 3H7Z"/>
                        </svg>
                        Exportar PDF
                    </button>
                    <button @click="exportReport('excel')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4C4 3.44772 4.44772 3 5 3H15C15.5523 3 16 3.44772 16 4V16C16 16.5523 15.5523 17 15 17H5C4.44772 17 4 16.5523 4 16V4Z"/>
                        </svg>
                        Exportar Excel
                    </button>
                </div>
            </div>

            <!-- Filtros del Reporte -->
            <form @submit.prevent="generateReport" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                    <input type="date" x-model="filters.startDate" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                    <input type="date" x-model="filters.endDate"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Departamento</label>
                    <select x-model="filters.department" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="ventas">Ventas</option>
                        <option value="logistica">Logística</option>
                        <option value="administracion">Administración</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                            :disabled="loading">
                        <span x-show="!loading">Generar Reporte</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            Procesando...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Resumen de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <template x-for="stat in stats" :key="stat.name">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div :class="stat.iconBackground" class="rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" x-html="stat.icon"></svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate" x-text="stat.name"></dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900" x-text="stat.value"></div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Tabla de Resultados -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Encabezados de tabla aquí -->
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceReport', () => ({
        loading: false,
        filters: {
            startDate: new Date().toISOString().split('T')[0],
            endDate: new Date().toISOString().split('T')[0],
            department: ''
        },
        stats: [],
        attendanceData: [],

        async generateReport() {
            this.loading = true;
            try {
                const response = await fetch('/attendance/generate-report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.filters)
                });

                const data = await response.json();
                if (data.success) {
                    this.stats = data.stats;
                    this.attendanceData = data.attendanceData;
                    this.$dispatch('notify', {
                        type: 'success',
                        message: 'Reporte generado exitosamente'
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message
                });
            } finally {
                this.loading = false;
            }
        },

        async exportReport(format) {
            try {
                window.location.href = `/attendance/export-report/${format}?${new URLSearchParams(this.filters)}`;
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al exportar el reporte'
                });
            }
        }
    }));
});
</script>