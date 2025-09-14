
<?php require_once '../layouts/app.php'; ?>

<div x-data="attendanceIndex" class="min-h-screen bg-gray-50">
    <!-- Encabezado -->
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">
                    Control de Asistencias
                </h1>
                <div class="flex space-x-3">
                    <button @click="openModal('check-in')" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Registrar Entrada
                    </button>
                    <button @click="openModal('check-out')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Registrar Salida
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Tarjetas de Estadísticas -->
        <?php require_once 'components/stats-cards.php'; ?>

        <!-- Filtros -->
        <?php require_once 'components/filters.php'; ?>

        <!-- Tabla de Asistencias -->
        <?php require_once 'components/attendance-table.php'; ?>
    </div>

    <!-- Modales -->
    <?php 
        require_once 'modals/check-in.php';
        require_once 'modals/check-out.php';
        require_once 'modals/details.php';
    ?>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceIndex', () => ({
        loading: false,
        currentModal: null,

        openModal(modal) {
            this.currentModal = modal;
        },

        async registerAttendance(type, formData) {
            this.loading = true;
            try {
                const response = await fetch(`/attendance/${type}`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                if (result.success) {
                    this.$dispatch('notify', {
                        type: 'success',
                        message: result.message
                    });
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message
                });
            } finally {
                this.loading = false;
                this.currentModal = null;
            }
        }
    }));
});
</script>