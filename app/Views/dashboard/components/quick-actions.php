
<div x-data="quickActions" class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">
            Acciones Rápidas
        </h3>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <!-- Registrar Entrada -->
            <button @click="$dispatch('open-modal', 'check-in')"
                    class="relative rounded-lg p-4 flex flex-col items-center text-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <div class="bg-indigo-100 rounded-lg p-3 mb-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Registrar Entrada</span>
            </button>

            <!-- Registrar Salida -->
            <button @click="$dispatch('open-modal', 'check-out')"
                    class="relative rounded-lg p-4 flex flex-col items-center text-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <div class="bg-green-100 rounded-lg p-3 mb-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Registrar Salida</span>
            </button>

            <!-- Reporte del Día -->
            <button @click="generateDailyReport"
                    class="relative rounded-lg p-4 flex flex-col items-center text-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <div class="bg-yellow-100 rounded-lg p-3 mb-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Reporte del Día</span>
            </button>

            <!-- Nuevo Empleado -->
            <button @click="window.location.href='/employees/create'"
                    class="relative rounded-lg p-4 flex flex-col items-center text-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <div class="bg-blue-100 rounded-lg p-3 mb-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Nuevo Empleado</span>
            </button>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Actividad Reciente</h4>
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
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 x-html="getActivityIcon(activity.type)"></svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500" x-text="activity.description"></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time x-text="formatTime(activity.time)"></time>
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

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quickActions', () => ({
        recentActivity: [],

        init() {
            this.loadRecentActivity();
            // Actualizar cada 5 minutos
            setInterval(() => this.loadRecentActivity(), 300000);
        },

        async loadRecentActivity() {
            try {
                const response = await fetch('/dashboard/recent-activity');
                const data = await response.json();
                if (data.success) {
                    this.recentActivity = data.activities;
                }
            } catch (error) {
                console.error('Error loading recent activity:', error);
            }
        },

        async generateDailyReport() {
            try {
                window.location.href = '/reports/daily/download';
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al generar el reporte'
                });
            }
        },

        getActivityIconClass(type) {
            return {
                'check-in': 'bg-green-500 text-white',
                'check-out': 'bg-blue-500 text-white',
                'new-employee': 'bg-indigo-500 text-white',
                'report': 'bg-yellow-500 text-white'
            }[type] || 'bg-gray-500 text-white';
        },

        getActivityIcon(type) {
            const icons = {
                'check-in': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />',
                'check-out': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />',
                'new-employee': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />',
                'report': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />'
            };
            return icons[type] || icons['report'];
        },

        formatTime(timestamp) {
            return new Date(timestamp).toLocaleTimeString('es-PE', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }));
});
</script>