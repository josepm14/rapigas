<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Exception;
use TCPDF;

/**
 * Controlador de Reportes
 * Gestiona la generación y visualización de reportes del sistema
 */
class ReportController extends BaseController 
{
    private $employeeModel;
    private $attendanceModel;

    public function __construct() 
    {
        parent::__construct();
        $this->employeeModel = new Employee($this->db);
        $this->attendanceModel = new Attendance($this->db);
    }

    /**
     * Muestra la página principal de reportes
     */
    public function index() 
    {
        try {
            if (!$this->hasRole(['administrador', 'supervisor'])) {
                throw new Exception('Acceso no autorizado');
            }

            $this->render('reports/index', [
                'departments' => $this->employeeModel->getAllDepartments()
            ]);

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/dashboard');
        }
    }

    /**
     * Genera reporte de asistencia
     */
    public function attendanceReport() 
    {
        try {
            if (!$this->hasRole(['administrador', 'supervisor'])) {
                throw new Exception('Acceso no autorizado');
            }

            $filters = [
                'fecha_inicio' => $_GET['fecha_inicio'] ?? date('Y-m-01'),
                'fecha_fin' => $_GET['fecha_fin'] ?? date('Y-m-t'),
                'departamento' => $_GET['departamento'] ?? null,
                'estado' => $_GET['estado'] ?? null
            ];

            $data = $this->attendanceModel->getReportData($filters);
            
            if (isset($_GET['format']) && $_GET['format'] === 'pdf') {
                $this->generatePDF('Reporte de Asistencia', $data);
            } else {
                $this->render('reports/attendance', [
                    'data' => $data,
                    'filters' => $filters
                ]);
            }

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/reports');
        }
    }

    /**
     * Genera reporte de empleados
     */
    public function employeeReport() 
    {
        try {
            if (!$this->hasRole(['administrador', 'supervisor'])) {
                throw new Exception('Acceso no autorizado');
            }

            $filters = [
                'departamento' => $_GET['departamento'] ?? null,
                'estado' => $_GET['estado'] ?? null
            ];

            $data = $this->employeeModel->getReportData($filters);

            if (isset($_GET['format']) && $_GET['format'] === 'pdf') {
                $this->generatePDF('Reporte de Empleados', $data);
            } else {
                $this->render('reports/employees', [
                    'data' => $data,
                    'filters' => $filters
                ]);
            }

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/reports');
        }
    }

    /**
     * Genera PDF con los datos proporcionados
     */
    private function generatePDF(string $title, array $data): void 
    {
        try {
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
            
            // Configuración del documento
            $pdf->SetCreator('RAPIGAS v2.0');
            $pdf->SetAuthor('Sistema de Gestión');
            $pdf->SetTitle($title);
            
            // Configuración de la página
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            
            // Encabezado
            $pdf->Cell(0, 10, $title, 0, 1, 'C');
            $pdf->Ln(10);
            
            // Contenido (personalizar según el tipo de reporte)
            foreach ($data as $row) {
                foreach ($row as $cell) {
                    $pdf->Cell(40, 10, $cell, 1);
                }
                $pdf->Ln();
            }
            
            // Salida del documento
            $pdf->Output('reporte.pdf', 'D');

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            throw new Exception('Error al generar el PDF');
        }
    }
}