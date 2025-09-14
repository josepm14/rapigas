<?php


namespace App\Controllers;

use PDO;
use Exception;

/**
 * Controlador Base
 * Proporciona funcionalidad común para todos los controladores
 */
class BaseController 
{
    /** @var PDO Conexión a la base de datos */
    protected $db;

    /** @var array Datos para la vista */
    protected $viewData = [];

    /** @var string Mensaje de éxito */
    protected $successMessage;

    /** @var string Mensaje de error */
    protected $errorMessage;

    /**
     * Constructor
     * Inicializa la conexión a la base de datos y la sesión
     */
    public function __construct() 
    {
        $this->initDatabase();
        $this->initSession();
    }

    /**
     * Inicializa la conexión a la base de datos
     * @throws Exception si hay error de conexión
     */
    private function initDatabase(): void 
    {
        try {
            $this->db = \App\Config\Database::getInstance()->getConnection();
        } catch (Exception $e) {
            $this->logError('Error de conexión a la base de datos: ' . $e->getMessage());
            throw new Exception('Error del sistema. Por favor, contacte al administrador.');
        }
    }

    /**
     * Inicializa la sesión si no está activa
     */
    protected function initSession(): void 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Renderiza una vista
     * @param string $view Ruta de la vista
     * @param array $data Datos para la vista
     */
    protected function render(string $view, array $data = []): void 
    {
        $this->viewData = array_merge($this->viewData, $data);
        extract($this->viewData);

        $viewPath = "../app/Views/{$view}.php";
        if (!file_exists($viewPath)) {
            throw new Exception("Vista no encontrada: {$view}");
        }

        require_once '../app/Views/layouts/header.php';
        require_once $viewPath;
        require_once '../app/Views/layouts/footer.php';
    }

    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    protected function isAuthenticated(): bool 
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Verifica si el usuario tiene el rol requerido
     * @param string|array $roles Rol o roles requeridos
     * @return bool
     */
    protected function hasRole($roles): bool 
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $roles = (array) $roles;
        return in_array($_SESSION['role'], $roles);
    }

    /**
     * Redirecciona a una URL específica
     * @param string $url URL de destino
     */
    protected function redirect(string $url): void 
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Establece mensaje de éxito en la sesión
     * @param string $message Mensaje
     */
    protected function setSuccessMessage(string $message): void 
    {
        $_SESSION['success'] = $message;
    }

    /**
     * Establece mensaje de error en la sesión
     * @param string $message Mensaje
     */
    protected function setErrorMessage(string $message): void 
    {
        $_SESSION['error'] = $message;
    }

    /**
     * Registra un error en el archivo de log
     * @param string $message Mensaje de error
     */
    protected function logError(string $message): void 
    {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logMessage = sprintf(
            "[%s] %s\n",
            date('Y-m-d H:i:s'),
            $message
        );
        
        error_log($logMessage, 3, $logDir . '/error.log');
    }
}