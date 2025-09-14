<?php

namespace App\Config;

class App {
    // Configuración de la aplicación
    public const APP_NAME = 'RAPIGAS v2.0';
    public const APP_VERSION = '2.0.0';
    public const APP_TIMEZONE = 'America/Lima';
    
    // Configuración de rutas
    public const BASE_URL = 'http://localhost/rapigas_v2.0';
    public const DEFAULT_CONTROLLER = 'Dashboard';
    public const DEFAULT_ACTION = 'index';
    
    // Configuración de sesión
    public const SESSION_LIFETIME = 3600; // 1 hora
    public const SESSION_NAME = 'RAPIGAS_SESSION';
    
    // Configuración de errores
    public const DEBUG_MODE = true;
    public const ERROR_REPORTING = E_ALL;
    
    public static function init() {
        // Configuración inicial
        date_default_timezone_set(self::APP_TIMEZONE);
        session_name(self::SESSION_NAME);
        
        // Configuración de errores
        if (self::DEBUG_MODE) {
            error_reporting(self::ERROR_REPORTING);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
        
        // Iniciar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => self::SESSION_LIFETIME,
                'cookie_httponly' => true,
                'cookie_secure' => !empty($_SERVER['HTTPS']),
                'cookie_samesite' => 'Lax'
            ]);
        }
    }
}