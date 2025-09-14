<?php

namespace App\Helpers;

use PDO;
use Exception;

class NotificationHelper 
{
    /**
     * Tipos de notificación disponibles
     */
    private const TIPOS = [
        'informacion',
        'advertencia',
        'error',
        'exito'
    ];

    /**
     * Crea una nueva notificación
     */
    public static function crear(
        int $usuario_id, 
        string $titulo, 
        string $mensaje, 
        string $tipo = 'informacion'
    ): bool {
        try {
            if (!in_array($tipo, self::TIPOS)) {
                throw new Exception('Tipo de notificación inválido');
            }

            $db = \App\Config\Database::getInstance()->getConnection();
            
            $sql = "INSERT INTO notificaciones (usuario_id, titulo, mensaje, tipo) 
                   VALUES (:usuario_id, :titulo, :mensaje, :tipo)";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                'usuario_id' => $usuario_id,
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'tipo' => $tipo
            ]);

        } catch (Exception $e) {
            error_log("Error al crear notificación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene notificaciones no leídas de un usuario
     */
    public static function obtenerNoLeidas(int $usuario_id): array 
    {
        try {
            $db = \App\Config\Database::getInstance()->getConnection();
            
            $sql = "SELECT * FROM notificaciones 
                   WHERE usuario_id = :usuario_id 
                   AND leido_en IS NULL 
                   ORDER BY fecha_creacion DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute(['usuario_id' => $usuario_id]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error al obtener notificaciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marca una notificación como leída
     */
    public static function marcarLeida(int $notificacion_id): bool 
    {
        try {
            $db = \App\Config\Database::getInstance()->getConnection();
            
            $sql = "UPDATE notificaciones 
                   SET leido_en = CURRENT_TIMESTAMP 
                   WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute(['id' => $notificacion_id]);

        } catch (Exception $e) {
            error_log("Error al marcar notificación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía notificación por correo electrónico
     */
    public static function enviarEmail(string $email, string $titulo, string $mensaje): bool 
    {
        try {
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: RAPIGAS <no-reply@rapigas.com>',
                'X-Mailer: PHP/' . phpversion()
            ];

            return mail(
                $email,
                $titulo,
                self::getEmailTemplate($titulo, $mensaje),
                implode("\r\n", $headers)
            );

        } catch (Exception $e) {
            error_log("Error al enviar email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene la plantilla HTML para emails
     */
    private static function getEmailTemplate(string $titulo, string $mensaje): string 
    {
        return "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <h2 style='color: #333;'>{$titulo}</h2>
                    <p style='color: #666;'>{$mensaje}</p>
                    <hr>
                    <p style='font-size: 12px; color: #999;'>
                        Este es un mensaje automático de RAPIGAS v2.0
                    </p>
                </div>
            </body>
            </html>
        ";
    }

    /**
     * Elimina notificaciones antiguas
     */
    public static function limpiarNotificaciones(int $dias = 30): bool 
    {
        try {
            $db = \App\Config\Database::getInstance()->getConnection();
            
            $sql = "DELETE FROM notificaciones 
                   WHERE fecha_creacion < DATE_SUB(CURRENT_DATE, INTERVAL :dias DAY)
                   AND leido_en IS NOT NULL";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute(['dias' => $dias]);

        } catch (Exception $e) {
            error_log("Error al limpiar notificaciones: " . $e->getMessage());
            return false;
        }
    }
}