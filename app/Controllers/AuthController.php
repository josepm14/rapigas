<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class AuthController 
{
    private $db;
    private $userModel;

    public function __construct() 
    {
        $this->db = \App\Config\Database::getInstance()->getConnection();
        $this->userModel = new User($this->db);
    }

    /**
     * Muestra la página de login
     */
    public function showLogin() 
    {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        require_once '../app/Views/auth/login.php';
    }

    /**
     * Procesa el login
     */
    public function login() 
    {
        try {
            if (!isset($_POST['username']) || !isset($_POST['password'])) {
                throw new Exception('Usuario y contraseña son requeridos');
            }

            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = $_POST['password'];

            $user = $this->userModel->findByUsername($username);
            
            if (!$user || !password_verify($password, $user['contrasena_hash'])) {
                throw new Exception('Credenciales inválidas');
            }

            if ($user['estado'] !== 'activo') {
                throw new Exception('Usuario inactivo. Contacte al administrador');
            }

            // Iniciar sesión
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre_usuario'];
            $_SESSION['role'] = $user['rol'];

            // Actualizar último acceso
            $this->userModel->updateLastAccess($user['id']);

            header('Location: /dashboard');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /login');
            exit;
        }
    }

    /**
     * Cierra la sesión
     */
    public function logout() 
    {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }

    /**
     * Muestra formulario de recuperación de contraseña
     */
    public function showRecoveryForm() 
    {
        require_once '../app/Views/auth/recovery.php';
    }

    /**
     * Procesa la recuperación de contraseña
     */
    public function recoverPassword() 
    {
        try {
            if (!isset($_POST['email'])) {
                throw new Exception('Email es requerido');
            }

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            if (!$email) {
                throw new Exception('Email inválido');
            }

            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                throw new Exception('Email no encontrado');
            }

            // Generar token de recuperación
            $token = bin2hex(random_bytes(32));
            $this->userModel->setRecoveryToken($user['id'], $token);

            // Enviar email
            $this->sendRecoveryEmail($email, $token);

            $_SESSION['success'] = 'Se ha enviado un email con las instrucciones';
            header('Location: /login');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /recovery');
            exit;
        }
    }

    /**
     * Verifica si hay una sesión activa
     */
    private function isLoggedIn(): bool 
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

    /**
     * Envía email de recuperación
     */
    private function sendRecoveryEmail(string $email, string $token): void 
    {
        $to = $email;
        $subject = 'Recuperación de contraseña - RAPIGAS';
        $message = "Para recuperar tu contraseña, haz clic en el siguiente enlace:\n\n";
        $message .= "http://localhost/reset-password?token=" . $token;

        mail($to, $subject, $message);
    }
}