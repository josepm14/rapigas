<?php

namespace App\Helpers;

class ValidationHelper 
{
    /**
     * Reglas de validación comunes
     */
    private static array $reglas = [
        'dni' => '/^[0-9]{8}$/',
        'telefono' => '/^[0-9]{9}$/',
        'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'fecha' => '/^\d{4}-\d{2}-\d{2}$/'
    ];

    /**
     * Valida un DNI
     */
    public static function validarDNI(string $dni): bool 
    {
        return preg_match(self::$reglas['dni'], $dni) === 1;
    }

    /**
     * Valida un número de teléfono
     */
    public static function validarTelefono(string $telefono): bool 
    {
        return preg_match(self::$reglas['telefono'], $telefono) === 1;
    }

    /**
     * Valida un correo electrónico
     */
    public static function validarEmail(string $email): bool 
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valida una fecha en formato YYYY-MM-DD
     */
    public static function validarFecha(string $fecha): bool 
    {
        if (!preg_match(self::$reglas['fecha'], $fecha)) {
            return false;
        }
        return strtotime($fecha) !== false;
    }

    /**
     * Valida que los campos requeridos no estén vacíos
     */
    public static function validarRequeridos(array $data, array $campos): array 
    {
        $errores = [];
        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                $errores[] = "El campo {$campo} es requerido";
            }
        }
        return $errores;
    }

    /**
     * Valida la longitud de una cadena
     */
    public static function validarLongitud(string $texto, int $min, int $max): bool 
    {
        $longitud = mb_strlen($texto);
        return $longitud >= $min && $longitud <= $max;
    }

    /**
     * Valida un rango numérico
     */
    public static function validarRango(float $numero, float $min, float $max): bool 
    {
        return $numero >= $min && $numero <= $max;
    }

    /**
     * Sanitiza datos de entrada
     */
    public static function sanitizar(string $dato): string 
    {
        return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida datos de empleado
     */
    public static function validarEmpleado(array $data): array 
    {
        $errores = [];
        
        // Campos requeridos
        $requeridos = ['nombres', 'apellidos', 'dni', 'cargo', 'departamento'];
        $errores = array_merge($errores, self::validarRequeridos($data, $requeridos));

        // Validar DNI
        if (!empty($data['dni']) && !self::validarDNI($data['dni'])) {
            $errores[] = "El DNI no es válido";
        }

        // Validar teléfono
        if (!empty($data['telefono']) && !self::validarTelefono($data['telefono'])) {
            $errores[] = "El teléfono no es válido";
        }

        // Validar email
        if (!empty($data['email']) && !self::validarEmail($data['email'])) {
            $errores[] = "El email no es válido";
        }

        // Validar fecha de contratación
        if (!empty($data['fecha_contratacion']) && !self::validarFecha($data['fecha_contratacion'])) {
            $errores[] = "La fecha de contratación no es válida";
        }

        return $errores;
    }

    /**
     * Valida datos de asistencia
     */
    public static function validarAsistencia(array $data): array 
    {
        $errores = [];

        if (empty($data['empleado_id'])) {
            $errores[] = "El ID de empleado es requerido";
        }

        if (!empty($data['hora_entrada']) && !self::validarFecha($data['hora_entrada'])) {
            $errores[] = "La hora de entrada no es válida";
        }

        if (!empty($data['hora_salida']) && !self::validarFecha($data['hora_salida'])) {
            $errores[] = "La hora de salida no es válida";
        }

        return $errores;
    }
}