<div x-data="detailsModal"
     x-show="isOpen"
     @open-modal.window="handleOpen($event)"
     @keydown.escape.window="close"
     class="relative z-50">
    
    <!-- Fondo Oscuro -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                <!-- Encabezado -->
                <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                    <button @click="close" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                            Detalles de Asistencia
                        </h3>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="mt-6 space-y-6">
                    <!-- Información del Empleado -->
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-sm font-medium text-gray-500">Empleado</h4>
                        <p class="mt-1 text-sm text-gray-900" x-text="attendance.empleado_nombre"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="'DNI: ' + attendance.empleado_dni"></p>
                    </div>

                    <!-- Detalles de Horario -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Entrada</h4>
                            <p class="mt-1 text-sm text-gray-900" x-text="formatDateTime(attendance.hora_entrada)"></p>
                            <p class="mt-1 text-sm text-gray-500" x-text="attendance.ubicacion_entrada || 'No especificada'"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Salida</h4>
                            <p class="mt-1 text-sm text-gray-900" x-text="attendance.hora_salida ? formatDateTime(attendance.hora_salida) : 'Pendiente'"></p>
                            <p class="mt-1 text-sm text-gray-500" x-text="attendance.ubicacion_salida || 'No especificada'"></p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Estado</h4>
                        <span class="mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                              :class="getStatusClass(attendance.estado)">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2" :class="getStatusDotClass(attendance.estado)" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            <span x-text="capitalizeFirst(attendance.estado)"></span>
                        </span>
                    </div>

                    <!-- Observaciones -->
                    <div x-show="attendance.observaciones">
                        <h4 class="text-sm font-medium text-gray-500">Observaciones</h4>
                        <p class="mt-1 text-sm text-gray-900" x-text="attendance.observaciones"></p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-6">
                    <button type="button"
                            @click="close"
                            class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('detailsModal', () => ({
        isOpen: false,
        attendance: {},

        handleOpen(event) {
            if (event.detail.type === 'details') {
                this.attendance = event.detail.attendance;
                this.isOpen = true;
            }
        },

        close() {
            this.isOpen = false;
        },

        formatDateTime(dateString) {
            return new Date(dateString).toLocaleString('es-PE', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        },

        getStatusClass(status) {
            return {
                'puntual': 'bg-green-100 text-green-800',
                'tardanza': 'bg-yellow-100 text-yellow-800',
                'ausente': 'bg-red-100 text-red-800'
            }[status] || 'bg-gray-100 text-gray-800';
        },

        getStatusDotClass(status) {
            return {
                'puntual': 'text-green-400',
                'tardanza': 'text-yellow-400',
                'ausente': 'text-red-400'
            }[status] || 'text-gray-400';
        },

        capitalizeFirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    }));
});
</script>