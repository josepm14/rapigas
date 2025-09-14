
<div x-data="employeeCard" class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Employee Header -->
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-20 w-20">
                <img class="h-20 w-20 rounded-full object-cover" 
                     :src="employee.avatar || '/img/default-avatar.png'" 
                     :alt="employee.name">
            </div>
            <div class="ml-4 flex-1">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900" x-text="employee.name"></h2>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="employee.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                          x-text="employee.status === 'active' ? 'Activo' : 'Inactivo'">
                    </span>
                </div>
                <div class="mt-1 text-sm text-gray-500" x-text="employee.position"></div>
            </div>
        </div>
    </div>

    <!-- Employee Details -->
    <div class="border-t border-gray-200 px-6 py-4">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">DNI</dt>
                <dd class="mt-1 text-sm text-gray-900" x-text="employee.dni"></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900" x-text="employee.email"></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                <dd class="mt-1 text-sm text-gray-900" x-text="employee.phone"></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Departamento</dt>
                <dd class="mt-1 text-sm text-gray-900" x-text="employee.department"></dd>
            </div>
        </dl>
    </div>

    <!-- Last Activity -->
    <div class="bg-gray-50 px-6 py-4">
        <div class="text-sm">
            <dt class="font-medium text-gray-500">Última Actividad</dt>
            <dd class="mt-1">
                <div class="flex items-center text-gray-900">
                    <template x-if="employee.last_activity">
                        <div class="flex items-center">
                            <span :class="getActivityIconClass(employee.last_activity.type)"
                                  class="flex-shrink-0 mr-1.5 h-2 w-2 rounded-full"></span>
                            <span x-text="employee.last_activity.description"></span>
                            <span class="ml-2 text-gray-500" x-text="formatDate(employee.last_activity.timestamp)"></span>
                        </div>
                    </template>
                    <template x-if="!employee.last_activity">
                        <span class="text-gray-500">Sin actividad registrada</span>
                    </template>
                </div>
            </dd>
        </div>
    </div>

    <!-- Actions -->
    <div class="border-t border-gray-200 px-6 py-4">
        <div class="flex justify-end space-x-3">
            <button @click="viewDetails"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Ver Detalles
            </button>
            <button @click="editEmployee"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Editar
            </button>
            <button @click="deleteEmployee"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Eliminar
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeCard', () => ({
        employee: {},

        init() {
            // Receive employee data from parent component
            this.employee = this.$el.getAttribute('data-employee') 
                ? JSON.parse(this.$el.getAttribute('data-employee')) 
                : {};
        },

        getActivityIconClass(type) {
            return {
                'check-in': 'bg-green-400',
                'check-out': 'bg-blue-400',
                'late': 'bg-yellow-400',
                'absent': 'bg-red-400'
            }[type] || 'bg-gray-400';
        },

        formatDate(timestamp) {
            return new Date(timestamp).toLocaleString('es-PE', {
                day: '2-digit',
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        viewDetails() {
            window.location.href = `/employees/${this.employee.id}`;
        },

        editEmployee() {
            window.location.href = `/employees/${this.employee.id}/edit`;
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
                    // Notify parent component to refresh
                    this.$dispatch('employee-deleted', this.employee.id);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: error.message || 'Error al eliminar empleado'
                });
            }
        }
    }));
});
</script>