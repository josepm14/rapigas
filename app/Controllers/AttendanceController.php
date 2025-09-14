<?php

namespace App\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Exception;
use PDO;

class AttendanceController
{
    private $db;
    private $attendanceModel;
    private $employeeModel;

    public function __construct()
    {
        $this->db = \App\Config\Database::getInstance()->getConnection();
        $this->attendanceModel = new Attendance($this->db);
        $this->employeeModel = new Employee($this->db);
    }

    /**
     * Muestra la lista de asistencias
     */
    public function index()
    {
        try {
            $attendances = $this->attendanceModel->getAll();
            require_once '../app/Views/attendance/index.php';
        } catch (Exception $e) {
            $error = "Error al cargar las asistencias: " . $e->getMessage();
            require_once '../app/Views/shared/error.php';
        }
    }

    /**
     * Registra entrada de empleado
     */
    public function checkIn()
    {
        try {
            if (!isset($_POST['employee_id'])) {
                throw new Exception("ID de empleado requerido");
            }

            $employeeId = $_POST['employee_id'];
            $location = $_POST['location'] ?? null;

            // Validar si ya existe un registro de entrada para hoy
            if ($this->attendanceModel->hasCheckInToday($employeeId)) {
                throw new Exception("Ya se registró la entrada para hoy");
            }

            $data = [
                'empleado_id' => $employeeId,
                'hora_entrada' => date('Y-m-d H:i:s'),
                'ubicacion_entrada' => $location,
                'estado' => $this->determineStatus(date('H:i:s'))
            ];

            $this->attendanceModel->create($data);
            
            echo json_encode(['status' => 'success', 'message' => 'Entrada registrada correctamente']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Registra salida de empleado
     */
    public function checkOut()
    {
        try {
            if (!isset($_POST['employee_id'])) {
                throw new Exception("ID de empleado requerido");
            }

            $employeeId = $_POST['employee_id'];
            $location = $_POST['location'] ?? null;

            // Obtener registro de entrada
            $attendance = $this->attendanceModel->getCurrentDayAttendance($employeeId);
            if (!$attendance) {
                throw new Exception("No existe registro de entrada para hoy");
            }

            $data = [
                'id' => $attendance['id'],
                'hora_salida' => date('Y-m-d H:i:s'),
                'ubicacion_salida' => $location
            ];

            $this->attendanceModel->update($data);
            
            echo json_encode(['status' => 'success', 'message' => 'Salida registrada correctamente']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Determina el estado de la asistencia según la hora
     */
    private function determineStatus(string $time): string
    {
        $entryTime = strtotime('09:00:00');
        $currentTime = strtotime($time);
        
        if ($currentTime <= $entryTime) {
            return 'puntual';
        }
        return 'tardanza';
    }

    /**
     * Genera reporte de asistencias
     */
    public function report()
    {
        try {
            $startDate = $_GET['start_date'] ?? date('Y-m-01');
            $endDate = $_GET['end_date'] ?? date('Y-m-t');
            $employeeId = $_GET['employee_id'] ?? null;

            $report = $this->attendanceModel->getReport($startDate, $endDate, $employeeId);
            require_once '../app/Views/attendance/report.php';
        } catch (Exception $e) {
            $error = "Error al generar el reporte: " . $e->getMessage();
            require_once '../app/Views/shared/error.php';
        }
    }
}