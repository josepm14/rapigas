<?php

namespace App\Services;

use App\Models\Employee;
use App\Helpers\ValidationHelper;
use Exception;
use PDO;

class EmployeeService 
{
    private $employeeModel;

    public function __construct(PDO $db) 
    {
        $this->employeeModel = new Employee($db);
    }

    /**
     * Crea un nuevo empleado
     */
    public function createEmployee(array $data): array 
    {
        try {
            // Validar datos
            $errors = ValidationHelper::validarEmpleado($data);
            if (!empty($errors)) {
                throw new Exception(implode(", ", $errors));
            }

            // Verificar DNI duplicado
            if ($this->employeeModel->isDNIDuplicate($data['dni'])) {
                throw new Exception("El DNI ya está registrado");
            }

            // Sanitizar datos
            $employeeData = [
                'nombres' => ValidationHelper::sanitizar($data['nombres']),
                'apellidos' => ValidationHelper::sanitizar($data['apellidos']),
                'dni' => $data['dni'],
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'genero' => $data['genero'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'direccion' => ValidationHelper::sanitizar($data['direccion'] ?? ''),
                'cargo' => ValidationHelper::sanitizar($data['cargo']),
                'departamento' => ValidationHelper::sanitizar($data['departamento']),
                'fecha_contratacion' => $data['fecha_contratacion'],
                'salario' => $data['salario'] ?? null,
                'estado' => 'activo'
            ];

            $id = $this->employeeModel->create($employeeData);

            return [
                'success' => true,
                'message' => 'Empleado creado exitosamente',
                'data' => ['id' => $id]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Actualiza un empleado existente
     */
    public function updateEmployee(int $id, array $data): array 
    {
        try {
            // Verificar existencia
            $employee = $this->employeeModel->findById($id);
            if (!$employee) {
                throw new Exception("Empleado no encontrado");
            }

            // Validar datos
            $errors = ValidationHelper::validarEmpleado($data);
            if (!empty($errors)) {
                throw new Exception(implode(", ", $errors));
            }

            // Verificar DNI duplicado
            if ($data['dni'] !== $employee['dni'] && 
                $this->employeeModel->isDNIDuplicate($data['dni'], $id)) {
                throw new Exception("El DNI ya está registrado");
            }

            // Actualizar datos
            $employeeData = [
                'id' => $id,
                'nombres' => ValidationHelper::sanitizar($data['nombres']),
                'apellidos' => ValidationHelper::sanitizar($data['apellidos']),
                'dni' => $data['dni'],
                'telefono' => $data['telefono'] ?? null,
                'direccion' => ValidationHelper::sanitizar($data['direccion'] ?? ''),
                'cargo' => ValidationHelper::sanitizar($data['cargo']),
                'departamento' => ValidationHelper::sanitizar($data['departamento']),
                'salario' => $data['salario'] ?? null,
                'estado' => $data['estado']
            ];

            $this->employeeModel->update($employeeData);

            return [
                'success' => true,
                'message' => 'Empleado actualizado exitosamente'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene empleados con filtros
     */
    public function getEmployees(array $filters = []): array 
    {
        try {
            $employees = $this->employeeModel->getAll($filters);
            return [
                'success' => true,
                'data' => $employees
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener empleados: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene estadísticas de empleados
     */
    public function getEmployeeStats(): array 
    {
        try {
            $stats = [
                'total' => $this->employeeModel->count(),
                'activos' => $this->employeeModel->countByStatus('activo'),
                'vacaciones' => $this->employeeModel->countByStatus('vacaciones'),
                'permisos' => $this->employeeModel->countByStatus('permiso'),
                'cesados' => $this->employeeModel->countByStatus('cesado')
            ];

            return [
                'success' => true,
                'data' => $stats
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ];
        }
    }
}