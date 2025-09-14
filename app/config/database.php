<?php

namespace App\Config;

use PDO;
use PDOException;
use Exception;

/**
 * Class Database
 * Implementa el patrón Singleton para la conexión a la base de datos
 */
class Database 
{
    /** @var Database|null */
    private static ?Database $instance = null;
    
    /** @var PDO|null */
    private ?PDO $connection = null;

    /**
     * Constructor privado
     * @throws Exception si la conexión falla
     */
    private function __construct() 
    {
        $this->loadEnvironmentVars();
        $this->connect();
    }

    /**
     * Carga variables de entorno
     */
    private function loadEnvironmentVars(): void 
    {
        $envFile = __DIR__ . '/.env';
        if (!file_exists($envFile)) {
            throw new Exception('Archivo .env no encontrado');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }

    /**
     * Establece la conexión a la base de datos
     * @throws Exception
     */
    private function connect(): void 
    {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                $_ENV['DB_HOST'] ?? 'localhost',
                $_ENV['DB_NAME'] ?? 'rapigas_db',
                $_ENV['DB_CHARSET'] ?? 'utf8mb4'
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            $this->connection = new PDO(
                $dsn,
                $_ENV['DB_USER'] ?? 'root',
                $_ENV['DB_PASS'] ?? '',
                $options
            );

        } catch (PDOException $e) {
            $this->logError($e);
            throw new Exception(
                "Error de conexión a la base de datos. Por favor, contacte al administrador."
            );
        }
    }

    /**
     * Registra errores en el archivo de log
     * @param PDOException $e
     */
    private function logError(PDOException $e): void 
    {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logMessage = sprintf(
            "[%s] Database Error: %s in %s:%d\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
        
        error_log($logMessage, 3, $logDir . '/database.log');
    }

    /**
     * Obtiene la instancia de la base de datos
     * @return Database
     */
    public static function getInstance(): Database 
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtiene la conexión a la base de datos
     * @return PDO
     * @throws Exception si la conexión no está establecida
     */
    public function getConnection(): PDO 
    {
        if ($this->connection === null) {
            throw new Exception("La conexión no está establecida");
        }
        return $this->connection;
    }

    private function __clone() {}
    
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}