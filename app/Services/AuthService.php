<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\AuthHelper;
use Exception;
use PDO;

class AuthService 
{
    private $userModel;
    private const MAX_LOGIN_ATTEMPTS = 3;
    private const LOCKOUT_TIME = 900; // 15 minutes in seconds

    public function __construct(PDO $db) 
    {
        $this->userModel = new User($db);
    }

    /**
     * Intenta autenticar al usuario
     */
    public function login(string $username, string $password): array 
    {
        try {
            // Validar intentos de login
            if ($this->isUserLocked($username)) {
                throw new Exception('Cuenta bloqueada temporalmente. Intente más tarde.');
            }

            // Buscar usuario
            $user = $this->userModel->findByUsername($username);
            if (!$user) {
                $this->incrementLoginAttempts($username);
                throw new Exception('Credenciales inválidas');
            }

            // Verificar contraseña
            if (!AuthHelper::verifyPassword($password, $user['contrasena_hash'])) {
                $this->incrementLoginAttempts($username);
                throw new Exception('Credenciales inválidas');
            }

            // Verificar estado
            if ($user['estado'] !== 'activo') {
                throw new Exception('Usuario inactivo. Contacte al administrador.');
            }

            // Actualizar acceso
            $this->userModel->updateLastAccess($user['id']);
            $this->resetLoginAttempts($username);

            // Iniciar sesión
            AuthHelper::login($user);

            return [
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['nombre_usuario'],
                    'role' => $user['rol']
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Inicia proceso de recuperación de contraseña
     */
    public function initiatePasswordRecovery(string $email): array 
    {
        try {
            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                throw new Exception('Email no encontrado');
            }

            $token = AuthHelper::generateToken();
            $this->userModel->setRecoveryToken($user['id'], $token);

            // Enviar email
            $recoveryLink = "http://localhost/reset-password?token=" . $token;
            $emailSent = mail(
                $email,
                'Recuperación de Contraseña - RAPIGAS',
                "Para recuperar su contraseña, haga clic en el siguiente enlace:\n\n" . $recoveryLink
            );

            if (!$emailSent) {
                throw new Exception('Error al enviar email');
            }

            return [
                'success' => true,
                'message' => 'Se ha enviado un email con las instrucciones'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cambia la contraseña del usuario
     */
    public function changePassword(int $userId, string $oldPassword, string $newPassword): array 
    {
        try {
            $user = $this->userModel->findById($userId);
            if (!$user) {
                throw new Exception('Usuario no encontrado');
            }

            if (!AuthHelper::verifyPassword($oldPassword, $user['contrasena_hash'])) {
                throw new Exception('Contraseña actual incorrecta');
            }

            $newHash = AuthHelper::hashPassword($newPassword);
            $this->userModel->changePassword($userId, $newHash);

            return [
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica si un usuario está bloqueado
     */
    private function isUserLocked(string $username): bool 
    {
        $attempts = $_SESSION['login_attempts'][$username] ?? 0;
        $lastAttempt = $_SESSION['last_attempt'][$username] ?? 0;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            if (time() - $lastAttempt < self::LOCKOUT_TIME) {
                return true;
            }
            $this->resetLoginAttempts($username);
        }

        return false;
    }

    /**
     * Incrementa los intentos de login fallidos
     */
    private function incrementLoginAttempts(string $username): void 
    {
        $_SESSION['login_attempts'][$username] = ($_SESSION['login_attempts'][$username] ?? 0) + 1;
        $_SESSION['last_attempt'][$username] = time();
    }

    /**
     * Reinicia los intentos de login
     */
    private function resetLoginAttempts(string $username): void 
    {
        unset($_SESSION['login_attempts'][$username]);
        unset($_SESSION['last_attempt'][$username]);
    }
}