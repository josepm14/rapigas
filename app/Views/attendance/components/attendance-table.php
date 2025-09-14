<div x-data="attendanceTable" class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Tabla de Asistencias -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Empleado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Entrada
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salida
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($attendances)): ?>
                    <?php foreach ($attendances as $attendance): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($attendance['empleado_nombre']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($attendance['empleado_dni']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y', strtotime($attendance['fecha'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('H:i:s', strtotime($attendance['hora_entrada'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $attendance['hora_salida'] ? date('H:i:s', strtotime($attendance['hora_salida'])) : '-'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo getStatusClass($attendance['estado']); ?>">
                                    <?php echo ucfirst($attendance['estado']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="showDetails(<?php echo htmlspecialchars(json_encode($attendance)); ?>)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Ver Detalles
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No se encontraron registros de asistencia
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <?php if (!empty($pagination)): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <?php if ($pagination['currentPage'] > 1): ?>
                    <a href="?page=<?php echo $pagination['currentPage'] - 1; ?>" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Anterior
                    </a>
                <?php endif; ?>
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="?page=<?php echo $pagination['currentPage'] + 1; ?>" 
                       class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Siguiente
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
function getStatusClass($status) {
    return match($status) {
        'puntual' => 'bg-green-100 text-green-800',
        'tardanza' => 'bg-yellow-100 text-yellow-800',
        'ausente' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800'
    };
}
?>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceTable', () => ({
        showDetails(attendance) {
            this.$dispatch('open-modal', { 
                type: 'details',
                attendance: attendance
            });
        }
    }));
});
</script>