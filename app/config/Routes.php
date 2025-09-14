<?php

namespace App\Config;

class Routes {
    private static $routes = [];
    
    public static function init() {
        // Rutas de autenticación
        self::$routes['GET']['/login'] = ['AuthController', 'showLogin'];
        self::$routes['POST']['/login'] = ['AuthController', 'login'];
        self::$routes['GET']['/logout'] = ['AuthController', 'logout'];
        
        // Rutas del dashboard
        self::$routes['GET']['/'] = ['DashboardController', 'index'];
        self::$routes['GET']['/dashboard'] = ['DashboardController', 'index'];
        
        // Rutas de empleados
        self::$routes['GET']['/empleados'] = ['EmployeeController', 'index'];
        self::$routes['GET']['/empleados/crear'] = ['EmployeeController', 'create'];
        self::$routes['POST']['/empleados/guardar'] = ['EmployeeController', 'store'];
        self::$routes['GET']['/empleados/editar/{id}'] = ['EmployeeController', 'edit'];
        self::$routes['POST']['/empleados/actualizar/{id}'] = ['EmployeeController', 'update'];
        
        // Rutas de asistencia
        self::$routes['GET']['/asistencias'] = ['AttendanceController', 'index'];
        self::$routes['POST']['/asistencias/registrar'] = ['AttendanceController', 'register'];
    }
    
    public static function getRoute(string $method, string $path): ?array {
        return self::$routes[strtoupper($method)][$path] ?? null;
    }
}