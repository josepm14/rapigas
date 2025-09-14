<?php require_once '../layouts/app.php'; ?>

<div x-data="dashboard" class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Empleados -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Empleados
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900" x-text="stats.totalEmployees">
                                            0
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Asistencias Hoy -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Asistencias Hoy
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900" x-text="stats.attendancesToday">
                                            0
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tardanzas Hoy -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Tardanzas Hoy
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900" x-text="stats.latesToday">
                                            0
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ausencias Hoy -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Ausencias Hoy
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900" x-text="stats.absencesToday">
                                            0
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos y Tablas -->
            <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <!-- Gráfico de Asistencias -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Tendencia de Asistencias</h3>
                            <div class="flex space-x-3">
                                <button @click="updateChart('week')" 
                                        :class="{'bg-indigo-100 text-indigo-700': chartPeriod === 'week'}"
                                        class="px-3 py-1 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">
                                    Semana
                                </button>
                                <button @click="updateChart('month')"
                                        :class="{'bg-indigo-100 text-indigo-700': chartPeriod === 'month'}"
                                        class="px-3 py-1 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">
                                    Mes
                                </button>
                            </div>
                        </div>
                        <div class="mt-4" style="height: 300px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Últimos Registros -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Últimos Registros</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Empleado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hora
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="record in recentRecords" :key="record.id">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900" x-text="record.employee_name"></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500" x-text="record.type"></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500" x-text="formatTime(record.time)"></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                      :class="getStatusClass(record.status)">
                                                    <span x-text="record.status"></span>
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
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboard', () => ({
        stats: {
            totalEmployees: 0,
            attendancesToday: 0,
            latesToday: 0,
            absencesToday: 0
        },
        chartPeriod: 'week',
        recentRecords: [],
        chart: null,

        init() {
            this.loadStats();
            this.initChart();
            this.loadRecentRecords();

            // Actualizar datos cada 5 minutos
            setInterval(() => {
                this.loadStats();
                this.loadRecentRecords();
            }, 300000);
        },

        async loadStats() {
            try {
                const response = await fetch('/dashboard/stats');
                const data = await response.json();
                if (data.success) {
                    this.stats = data.stats;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        },

        async loadRecentRecords() {
            try {
                const response = await fetch('/dashboard/recent-records');
                const data = await response.json();
                if (data.success) {
                    this.recentRecords = data.records;
                }
            } catch (error) {
                console.error('Error loading records:', error);
            }
        },

        initChart() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Asistencias',
                        data: [],
                        borderColor: '#10B981',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            this.updateChart(this.chartPeriod);
        },

        async updateChart(period) {
            this.chartPeriod = period;
            try {
                const response = await fetch(`/dashboard/chart-data?period=${period}`);
                const data = await response.json();
                if (data.success) {
                    this.chart.data.labels = data.labels;
                    this.chart.data.datasets[0].data = data.values;
                    this.chart.update();
                }
            } catch (error) {
                console.error('Error updating chart:', error);
            }
        },

        formatTime(timestamp) {
            return new Date(timestamp).toLocaleTimeString('es-PE');
        },

        getStatusClass(status) {
            return {
                'puntual': 'bg-green-100 text-green-800',
                'tardanza': 'bg-yellow-100 text-yellow-800',
                'ausente': 'bg-red-100 text-red-800'
            }[status] || 'bg-gray-100 text-gray-800';
        }
    }));
});
</script>