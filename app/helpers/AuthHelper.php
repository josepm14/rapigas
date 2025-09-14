<?php

namespace App\Helpers;

class AuthHelper 
{
    /**
     * Verifica si el usuario está autenticado
     */
    public static function isAuthenticated(): bool 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    /**
     * Verifica si el usuario tiene el rol requerido
     */
    public static function hasRole(string|array $roles): bool 
    {
        if (!self::isAuthenticated()) {
            return false;
        }

        $roles = (array) $roles;
        return in_array($_SESSION['role'], $roles);
    }

    /**
     * Genera un hash seguro para la contraseña
     */
    public static function hashPassword(string $password): string 
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verifica si la contraseña coincide con el hash
     */
    public static function verifyPassword(string $password, string $hash): bool 
    {
        return password_verify($password, $hash);
    }

    /**
     * Genera un token seguro
     */
    public static function generateToken(int $length = 32): string 
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Inicia sesión para un usuario
     */
    public static function login(array $user): void 
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nombre_usuario'];
        $_SESSION['role'] = $user['rol'];
        $_SESSION['last_activity'] = time();
    }

    /**
     * Cierra la sesión actual
     */
    public static function logout(): void 
    {
        session_start();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }

    /**
     * Verifica si la sesión ha expirado
     */
    public static function checkSessionTimeout(int $timeout = 1800): bool 
    {
        if (!isset($_SESSION['last_activity'])) {
            return true;
        }

        if (time() - $_SESSION['last_activity'] > $timeout) {
            self::logout();
            return true;
        }

        $_SESSION['last_activity'] = time();
        return false;
    }

    /**
     * Sanea datos de entrada
     */
    public static function sanitizeInput(string $data): string 
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida el formato del correo electrónico
     */
    public static function validateEmail(string $email): bool 
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Obtiene el ID del usuario actual
     */
    public static function getCurrentUserId(): ?int 
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Obtiene el rol del usuario actual
     */
    public static function getCurrentUserRole(): ?string 
    {
        return $_SESSION['role'] ?? null;
    }
}