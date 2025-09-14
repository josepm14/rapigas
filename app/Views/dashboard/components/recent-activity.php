
<div x-data="recentActivity" class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
            <div class="flex space-x-2">
                <select x-model="filter" 
                        @change="filterActivities"
                        class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="all">Todas</option>
                    <option value="check-in">Entradas</option>
                    <option value="check-out">Salidas</option>
                    <option value="system">Sistema</option>
                </select>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center py-8">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
        </div>

        <!-- Activity Timeline -->
        <div x-show="!loading" class="flow-root">
            <ul role="list" class="-mb-8">
                <template x-for="(activity, index) in filteredActivities" :key="activity.id">
                    <li>
                        <div class="relative pb-8">
                            <!-- Timeline Connector -->
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" 
                                  x-show="index !== filteredActivities.length - 1"></span>
                            
                            <div class="relative flex space-x-3">
                                <!-- Activity Icon -->
                                <div>
                                    <span :class="getActivityIconClass(activity.type)"
                                          class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             x-html="getActivityIcon(activity.type)"></svg>
                                    </span>
                                </div>

                                <!-- Activity Content -->
                                <div class="min-w-0 flex-1 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-900" x-text="activity.user"></span>
                                            <span x-text="activity.description"></span>
                                        </p>
                                        <p x-show="activity.details" 
                                           class="mt-1 text-sm text-gray-500" 
                                           x-text="activity.details"></p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time x-text="formatTime(activity.timestamp)"></time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>

                <!-- Empty State -->
                <li x-show="filteredActivities.length === 0">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay actividades</h3>
                        <p class="mt-1 text-sm text-gray-500">No se encontraron registros para el filtro seleccionado.</p>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Load More Button -->
        <div x-show="hasMoreActivities" class="mt-6 text-center">
            <button @click="loadMore"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span x-show="!loadingMore">Cargar más</span>
                <svg x-show="loadingMore" class="animate-spin h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('recentActivity', () => ({
        loading: true,
        loadingMore: false,
        activities: [],
        filter: 'all',
        page: 1,
        hasMoreActivities: false,

        init() {
            this.loadActivities();
            // Auto-refresh cada 2 minutos
            setInterval(() => this.loadActivities(), 120000);
        },

        get filteredActivities() {
            if (this.filter === 'all') return this.activities;
            return this.activities.filter(activity => activity.type === this.filter);
        },

        async loadActivities() {
            if (this.loading) return;
            this.loading = true;

            try {
                const response = await fetch(`/dashboard/activities?page=${this.page}`);
                const data = await response.json();
                
                if (data.success) {
                    this.activities = data.activities;
                    this.hasMoreActivities = data.hasMore;
                }
            } catch (error) {
                console.error('Error loading activities:', error);
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al cargar las actividades'
                });
            } finally {
                this.loading = false;
            }
        },

        async loadMore() {
            if (this.loadingMore) return;
            this.loadingMore = true;
            this.page += 1;

            try {
                const response = await fetch(`/dashboard/activities?page=${this.page}`);
                const data = await response.json();
                
                if (data.success) {
                    this.activities = [...this.activities, ...data.activities];
                    this.hasMoreActivities = data.hasMore;
                }
            } catch (error) {
                console.error('Error loading more activities:', error);
            } finally {
                this.loadingMore = false;
            }
        },

        filterActivities() {
            this.page = 1;
            this.loadActivities();
        },

        getActivityIconClass(type) {
            return {
                'check-in': 'bg-green-500 text-white',
                'check-out': 'bg-blue-500 text-white',
                'system': 'bg-purple-500 text-white',
                'error': 'bg-red-500 text-white'
            }[type] || 'bg-gray-500 text-white';
        },

        getActivityIcon(type) {
            const icons = {
                'check-in': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />',
                'check-out': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />',
                'system': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />',
                'error': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
            };
            return icons[type] || icons['system'];
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMinutes = Math.floor((now - date) / 60000);

            if (diffMinutes < 1) return 'Justo ahora';
            if (diffMinutes < 60) return `Hace ${diffMinutes} min`;
            if (diffMinutes < 1440) {
                const hours = Math.floor(diffMinutes / 60);
                return `Hace ${hours} ${hours === 1 ? 'hora' : 'horas'}`;
            }

            return date.toLocaleDateString('es-PE', {
                day: '2-digit',
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }));
});
</script>