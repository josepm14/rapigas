<?php

namespace App\Controllers;

use App\Models\Employee;
use Exception;

/**
 * Controlador de Empleados
 * Gestiona el CRUD de empleados y sus operaciones relacionadas
 */
class EmployeeController extends BaseController 
{
    private $employeeModel;

    /**
     * Constructor
     * Inicializa el modelo de empleados
     */
    public function __construct() 
    {
        parent::__construct();
        $this->employeeModel = new Employee($this->db);
    }

    /**
     * Muestra la lista de empleados
     */
    public function index() 
    {
        try {
            if (!$this->hasRole(['administrador', 'supervisor'])) {
                $this->setErrorMessage('Acceso no autorizado');
                $this->redirect('/dashboard');
            }

            $filters = [
                'departamento' => $_GET['departamento'] ?? null,
                'estado' => $_GET['estado'] ?? null,
                'buscar' => $_GET['buscar'] ?? null
            ];

            $employees = $this->employeeModel->getAll($filters);
            $this->render('employees/index', ['employees' => $employees]);

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage('Error al cargar la lista de empleados');
            $this->render('employees/index', ['error' => true]);
        }
    }

    /**
     * Muestra el formulario de crear empleado
     */
    public function create() 
    {
        if (!$this->hasRole('administrador')) {
            $this->setErrorMessage('Acceso no autorizado');
            $this->redirect('/employees');
        }

        $this->render('employees/create');
    }

    /**
     * Guarda un nuevo empleado
     */
    public function store() 
    {
        try {
            if (!$this->hasRole('administrador')) {
                throw new Exception('Acceso no autorizado');
            }

            $this->validateEmployeeData($_POST);

            $data = [
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'dni' => $_POST['dni'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'genero' => $_POST['genero'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'cargo' => $_POST['cargo'],
                'departamento' => $_POST['departamento'],
                'fecha_contratacion' => $_POST['fecha_contratacion'],
                'salario' => $_POST['salario'],
                'estado' => 'activo'
            ];

            $this->employeeModel->create($data);
            $this->setSuccessMessage('Empleado creado exitosamente');
            $this->redirect('/employees');

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/employees/create');
        }
    }

    /**
     * Muestra el formulario de editar empleado
     */
    public function edit($id) 
    {
        try {
            if (!$this->hasRole('administrador')) {
                throw new Exception('Acceso no autorizado');
            }

            $employee = $this->employeeModel->getById($id);
            if (!$employee) {
                throw new Exception('Empleado no encontrado');
            }

            $this->render('employees/edit', ['employee' => $employee]);

        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/employees');
        }
    }

    /**
     * Actualiza un empleado
     */
    public function update($id) 
    {
        try {
            if (!$this->hasRole('administrador')) {
                throw new Exception('Acceso no autorizado');
            }

            $this->validateEmployeeData($_POST);

            $data = [
                'id' => $id,
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'cargo' => $_POST['cargo'],
                'departamento' => $_POST['departamento'],
                'salario' => $_POST['salario'],
                'estado' => $_POST['estado']
            ];

            $this->employeeModel->update($data);
            $this->setSuccessMessage('Empleado actualizado exitosamente');
            $this->redirect('/employees');

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage($e->getMessage());
            $this->redirect("/employees/edit/$id");
        }
    }

    /**
     * Elimina un empleado
     */
    public function delete($id) 
    {
        try {
            if (!$this->hasRole('administrador')) {
                throw new Exception('Acceso no autorizado');
            }

            $this->employeeModel->delete($id);
            $this->setSuccessMessage('Empleado eliminado exitosamente');

        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->setErrorMessage($e->getMessage());
        }

        $this->redirect('/employees');
    }

    /**
     * Valida los datos del empleado
     */
    private function validateEmployeeData($data): void 
    {
        $required = ['nombres', 'apellidos', 'dni', 'cargo', 'departamento'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("El campo {$field} es requerido");
            }
        }

        if (!empty($data['dni']) && !preg_match('/^[0-9]{8}$/', $data['dni'])) {
            throw new Exception('DNI inválido');
        }

        if (!empty($data['telefono']) && !preg_match('/^[0-9]{9}$/', $data['telefono'])) {
            throw new Exception('Teléfono inválido');
        }
    }
}