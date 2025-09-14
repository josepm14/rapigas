<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Exception;

/**
 * Controlador del Dashboard
 * Gestiona la vista principal y estadísticas del sistema
 */
class DashboardController extends BaseController 
{
    private $employeeModel;
    private $attendanceModel;

    /**
     * Constructor
     * Inicializa los modelos necesarios
     */
    public function __construct() 
    {
        parent::__construct();
        $this->employeeModel = new Employee($this->db);
        $this->attendanceModel = new Attendance($this->db);
    }

    /**
     * Muestra el dashboard principal
     */
    public function index() 
    {
        try {
            if (!$this->isAuthenticated()) {
                $this->redirect('/login');
            }

            // Obtener estadísticas generales
            $stats = $this->getGeneralStats();

            // Obtener datos para gráficos
            $chartData = $this->getChartData();

            // Obtener asistencias del día
            $todayAttendance = $this->getTodayAttendance();

            $this->render('dashboard/index', [
                'stats' => $stats,
                'chartData' => $chartData,
                'todayAttendance' => $todayAttendance,
                'userRole' => $_SESSION['role']
            ]);

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage('Error al cargar el dashboard');
            $this->render('dashboard/index', ['error' => true]);
        }
    }

    /**
     * Obtiene estadísticas generales del sistema
     */
    private function getGeneralStats(): array 
    {
        return [
            'totalEmployees' => $this->employeeModel->count(),
            'activeEmployees' => $this->employeeModel->countByStatus('activo'),
            'todayPresent' => $this->attendanceModel->countTodayPresent(),
            'todayAbsent' => $this->employeeModel->countByStatus('activo') - $this->attendanceModel->countTodayPresent(),
            'todayLate' => $this->attendanceModel->countTodayLate(),
            'pendingLeaves' => $this->employeeModel->countOnLeave()
        ];
    }

    /**
     * Obtiene datos para los gráficos del dashboard
     */
    private function getChartData(): array 
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        return [
            'attendanceByMonth' => $this->attendanceModel->getMonthlyStats($currentMonth, $currentYear),
            'attendanceByDepartment' => $this->attendanceModel->getDepartmentStats(),
            'lateByWeek' => $this->attendanceModel->getWeeklyLateStats(),
            'leavesByType' => $this->employeeModel->getLeaveStats()
        ];
    }

    /**
     * Obtiene las asistencias del día actual
     */
    private function getTodayAttendance(): array 
    {
        return $this->attendanceModel->getTodayRecords();
    }

    /**
     * Actualiza los datos del dashboard vía AJAX
     */
    public function updateStats() 
    {
        try {
            $stats = $this->getGeneralStats();
            echo json_encode([
                'status' => 'success',
                'data' => $stats
            ]);
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al actualizar estadísticas'
            ]);
        }
    }
}