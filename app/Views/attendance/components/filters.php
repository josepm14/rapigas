
<div x-data="attendanceFilters" class="bg-white rounded-lg shadow mb-6">
    <div class="p-4">
        <form @submit.prevent="applyFilters" class="space-y-4">
            <!-- Contenedor de Filtros -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filtro Fecha Inicio -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha Inicio
                    </label>
                    <input type="date" 
                           x-model="filters.fechaInicio"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Filtro Fecha Fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha Fin
                    </label>
                    <input type="date" 
                           x-model="filters.fechaFin"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Filtro Departamento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Departamento
                    </label>
                    <select x-model="filters.departamento"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="ventas">Ventas</option>
                        <option value="logistica">Logística</option>
                        <option value="administracion">Administración</option>
                    </select>
                </div>

                <!-- Filtro Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select x-model="filters.estado"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="puntual">Puntual</option>
                        <option value="tardanza">Tardanza</option>
                        <option value="ausente">Ausente</option>
                    </select>
                </div>
            </div>

            <!-- Barra de Acciones -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <button type="submit"
                            :disabled="loading"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                        <template x-if="!loading">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </template>
                        <template x-if="loading">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </template>
                        Aplicar Filtros
                    </button>
                    <button type="button"
                            @click="resetFilters"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Limpiar
                    </button>
                </div>
                
                <!-- Exportar -->
                <div class="flex items-center space-x-3">
                    <button type="button"
                            @click="exportData('excel')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                        </svg>
                        Excel
                    </button>
                    <button type="button"
                            @click="exportData('pdf')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                        </svg>
                        PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceFilters', () => ({
        loading: false,
        filters: {
            fechaInicio: new Date().toISOString().split('T')[0],
            fechaFin: new Date().toISOString().split('T')[0],
            departamento: '',
            estado: ''
        },

        async applyFilters() {
            this.loading = true;
            try {
                const queryParams = new URLSearchParams(this.filters);
                window.location.href = `?${queryParams.toString()}`;
            } catch (error) {
                console.error('Error al aplicar filtros:', error);
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al aplicar los filtros'
                });
            } finally {
                this.loading = false;
            }
        },

        resetFilters() {
            this.filters = {
                fechaInicio: new Date().toISOString().split('T')[0],
                fechaFin: new Date().toISOString().split('T')[0],
                departamento: '',
                estado: ''
            };
            this.applyFilters();
        },

        async exportData(format) {
            try {
                const queryParams = new URLSearchParams(this.filters);
                window.location.href = `/attendance/export/${format}?${queryParams.toString()}`;
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: `Error al exportar a ${format.toUpperCase()}`
                });
            }
        }
    }));
});
</script>