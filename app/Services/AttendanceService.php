<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Exception;
use PDO;

class AttendanceService
{
    private $attendanceModel;
    private $employeeModel;

    public function __construct(PDO $db)
    {
        $this->attendanceModel = new Attendance($db);
        $this->employeeModel = new Employee($db);
    }

    /**
     * Registra entrada de empleado
     */
    public function registerCheckIn(int $employeeId, ?string $location = null): array
    {
        try {
            // Validar empleado
            $employee = $this->employeeModel->findById($employeeId);
            if (!$employee) {
                throw new Exception("Empleado no encontrado");
            }

            // Verificar estado del empleado
            if ($employee['estado'] !== 'activo') {
                throw new Exception("Empleado no está activo");
            }

            // Verificar si ya registró entrada
            if ($this->attendanceModel->hasCheckInToday($employeeId)) {
                throw new Exception("Ya registró entrada hoy");
            }

            // Determinar estado de la entrada
            $currentTime = date('H:i:s');
            $status = $this->determineAttendanceStatus($currentTime);

            // Registrar asistencia
            $data = [
                'empleado_id' => $employeeId,
                'hora_entrada' => date('Y-m-d H:i:s'),
                'ubicacion_entrada' => $location,
                'estado' => $status
            ];

            $id = $this->attendanceModel->create($data);

            return [
                'success' => true,
                'message' => 'Entrada registrada correctamente',
                'data' => [
                    'id' => $id,
                    'estado' => $status,
                    'hora' => date('Y-m-d H:i:s')
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Registra salida de empleado
     */
    public function registerCheckOut(int $employeeId, ?string $location = null): array
    {
        try {
            // Obtener registro de entrada
            $attendance = $this->attendanceModel->getCurrentDayAttendance($employeeId);
            if (!$attendance) {
                throw new Exception("No se encontró registro de entrada para hoy");
            }

            if ($attendance['hora_salida']) {
                throw new Exception("Ya registró salida");
            }

            // Actualizar registro
            $data = [
                'id' => $attendance['id'],
                'hora_salida' => date('Y-m-d H:i:s'),
                'ubicacion_salida' => $location
            ];

            $this->attendanceModel->update($data);

            return [
                'success' => true,
                'message' => 'Salida registrada correctamente',
                'data' => [
                    'hora' => date('Y-m-d H:i:s')
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Determina el estado de la asistencia según la hora
     */
    private function determineAttendanceStatus(string $time): string
    {
        $entryTime = strtotime('09:00:00');
        $currentTime = strtotime($time);
        
        if ($currentTime <= $entryTime) {
            return 'puntual';
        }
        
        return 'tardanza';
    }

    /**
     * Genera reporte de asistencia
     */
    public function generateReport(array $filters = []): array
    {
        try {
            $attendances = $this->attendanceModel->getAll($filters);
            
            $stats = [
                'total' => count($attendances),
                'puntual' => 0,
                'tardanza' => 0,
                'ausente' => 0
            ];

            foreach ($attendances as $attendance) {
                $stats[$attendance['estado']]++;
            }

            return [
                'success' => true,
                'data' => [
                    'attendances' => $attendances,
                    'stats' => $stats
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al generar reporte: ' . $e->getMessage()
            ];
        }
    }
}