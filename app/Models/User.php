<?php

namespace App\Models;

use PDO;
use Exception;

class User extends BaseModel
{
    protected string $table = 'usuarios';

    /**
     * Encuentra usuario por nombre de usuario
     */
    public function findByUsername(string $username): ?array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table} 
                WHERE nombre_usuario = :username
            ");
            $stmt->execute(['username' => $username]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (Exception $e) {
            error_log("Error en User::findByUsername: " . $e->getMessage());
            throw new Exception("Error al buscar usuario");
        }
    }

    /**
     * Encuentra usuario por correo electrónico
     */
    public function findByEmail(string $email): ?array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table} 
                WHERE correo = :email
            ");
            $stmt->execute(['email' => $email]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (Exception $e) {
            error_log("Error en User::findByEmail: " . $e->getMessage());
            throw new Exception("Error al buscar usuario por correo");
        }
    }

    /**
     * Actualiza el último acceso del usuario
     */
    public function updateLastAccess(int $userId): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE {$this->table} 
                SET ultimo_acceso = CURRENT_TIMESTAMP 
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $userId]);

        } catch (Exception $e) {
            error_log("Error en User::updateLastAccess: " . $e->getMessage());
            throw new Exception("Error al actualizar último acceso");
        }
    }

    /**
     * Actualiza el token de recuperación de contraseña
     */
    public function setRecoveryToken(int $userId, string $token): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE {$this->table} 
                SET token_recuperacion = :token,
                    token_expiracion = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)
                WHERE id = :id
            ");
            return $stmt->execute([
                'id' => $userId,
                'token' => $token
            ]);

        } catch (Exception $e) {
            error_log("Error en User::setRecoveryToken: " . $e->getMessage());
            throw new Exception("Error al establecer token de recuperación");
        }
    }

    /**
     * Cambia la contraseña del usuario
     */
    public function changePassword(int $userId, string $newPasswordHash): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE {$this->table} 
                SET contrasena_hash = :password,
                    token_recuperacion = NULL,
                    token_expiracion = NULL
                WHERE id = :id
            ");
            return $stmt->execute([
                'id' => $userId,
                'password' => $newPasswordHash
            ]);

        } catch (Exception $e) {
            error_log("Error en User::changePassword: " . $e->getMessage());
            throw new Exception("Error al cambiar contraseña");
        }
    }

    /**
     * Obtiene usuarios por rol
     */
    public function findByRole(string $role): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, nombre_usuario, correo, estado, ultimo_acceso 
                FROM {$this->table} 
                WHERE rol = :role
            ");
            $stmt->execute(['role' => $role]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en User::findByRole: " . $e->getMessage());
            throw new Exception("Error al buscar usuarios por rol");
        }
    }
}