<div x-data="attendanceChart" 
     class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                Tendencia de Asistencias
            </h3>
            
            <!-- Filtros de Período -->
            <div class="inline-flex rounded-md shadow-sm">
                <button @click="changePeriod('week')" 
                        :class="{'bg-indigo-600 text-white': period === 'week', 'bg-white text-gray-700': period !== 'week'}"
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-l-md border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    Semana
                </button>
                <button @click="changePeriod('month')"
                        :class="{'bg-indigo-600 text-white': period === 'month', 'bg-white text-gray-700': period !== 'month'}"
                        class="relative -ml-px inline-flex items-center px-3 py-2 text-sm font-medium border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    Mes
                </button>
                <button @click="changePeriod('year')"
                        :class="{'bg-indigo-600 text-white': period === 'year', 'bg-white text-gray-700': period !== 'year'}"
                        class="relative -ml-px inline-flex items-center px-3 py-2 text-sm font-medium rounded-r-md border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    Año
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex items-center justify-center h-64">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Chart Container -->
        <div x-show="!loading" class="h-64">
            <canvas id="attendanceChart"></canvas>
        </div>

        <!-- Legend -->
        <div class="mt-4 flex items-center justify-center space-x-4">
            <div class="flex items-center">
                <span class="h-3 w-3 bg-green-500 rounded-full"></span>
                <span class="ml-2 text-sm text-gray-600">Puntuales</span>
            </div>
            <div class="flex items-center">
                <span class="h-3 w-3 bg-yellow-500 rounded-full"></span>
                <span class="ml-2 text-sm text-gray-600">Tardanzas</span>
            </div>
            <div class="flex items-center">
                <span class="h-3 w-3 bg-red-500 rounded-full"></span>
                <span class="ml-2 text-sm text-gray-600">Ausencias</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceChart', () => ({
        loading: true,
        period: 'week',
        chart: null,

        init() {
            this.initChart();
            this.loadData();
        },

        async loadData() {
            this.loading = true;
            try {
                const response = await fetch(`/dashboard/attendance-stats?period=${this.period}`);
                const data = await response.json();
                
                if (data.success) {
                    this.updateChart(data);
                }
            } catch (error) {
                console.error('Error loading chart data:', error);
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Error al cargar los datos del gráfico'
                });
            } finally {
                this.loading = false;
            }
        },

        initChart() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Puntuales',
                            data: [],
                            borderColor: '#10B981',
                            backgroundColor: '#10B98120',
                            tension: 0.4
                        },
                        {
                            label: 'Tardanzas',
                            data: [],
                            borderColor: '#F59E0B',
                            backgroundColor: '#F59E0B20',
                            tension: 0.4
                        },
                        {
                            label: 'Ausencias',
                            data: [],
                            borderColor: '#EF4444',
                            backgroundColor: '#EF444420',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        },

        updateChart(data) {
            this.chart.data.labels = data.labels;
            this.chart.data.datasets[0].data = data.onTime;
            this.chart.data.datasets[1].data = data.late;
            this.chart.data.datasets[2].data = data.absent;
            this.chart.update();
        },

        async changePeriod(newPeriod) {
            if (this.period === newPeriod) return;
            this.period = newPeriod;
            await this.loadData();
        }
    }));
});
</script>