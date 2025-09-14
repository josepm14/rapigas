<?php

namespace App\Models;

use PDO;
use Exception;

class Attendance extends BaseModel
{
    protected string $table = 'asistencias';

    /**
     * Obtiene todas las asistencias con filtros opcionales
     */
    public function getAll(array $filters = []): array
    {
        try {
            $sql = "SELECT a.*, e.nombres, e.apellidos 
                   FROM {$this->table} a
                   INNER JOIN empleados e ON a.empleado_id = e.id
                   WHERE 1=1";
            
            $params = [];

            if (!empty($filters['fecha_inicio'])) {
                $sql .= " AND DATE(a.hora_entrada) >= :fecha_inicio";
                $params['fecha_inicio'] = $filters['fecha_inicio'];
            }

            if (!empty($filters['fecha_fin'])) {
                $sql .= " AND DATE(a.hora_entrada) <= :fecha_fin";
                $params['fecha_fin'] = $filters['fecha_fin'];
            }

            if (!empty($filters['estado'])) {
                $sql .= " AND a.estado = :estado";
                $params['estado'] = $filters['estado'];
            }

            $sql .= " ORDER BY a.hora_entrada DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en Attendance::getAll: " . $e->getMessage());
            throw new Exception("Error al obtener asistencias");
        }
    }

    /**
     * Verifica si un empleado ya registró entrada hoy
     */
    public function hasCheckInToday(int $employeeId): bool
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} 
                   WHERE empleado_id = :empleado_id 
                   AND DATE(hora_entrada) = CURRENT_DATE";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['empleado_id' => $employeeId]);
            
            return (int)$stmt->fetchColumn() > 0;

        } catch (Exception $e) {
            error_log("Error en Attendance::hasCheckInToday: " . $e->getMessage());
            throw new Exception("Error al verificar asistencia");
        }
    }

    /**
     * Registra una nueva asistencia
     */
    public function create(array $data): int
    {
        try {
            $sql = "INSERT INTO {$this->table} 
                   (empleado_id, hora_entrada, ubicacion_entrada, estado) 
                   VALUES (:empleado_id, :hora_entrada, :ubicacion_entrada, :estado)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'empleado_id' => $data['empleado_id'],
                'hora_entrada' => $data['hora_entrada'],
                'ubicacion_entrada' => $data['ubicacion_entrada'] ?? null,
                'estado' => $data['estado']
            ]);
            
            return (int)$this->db->lastInsertId();

        } catch (Exception $e) {
            error_log("Error en Attendance::create: " . $e->getMessage());
            throw new Exception("Error al registrar asistencia");
        }
    }

    /**
     * Actualiza una asistencia existente
     */
    public function update(array $data): bool
    {
        try {
            $sql = "UPDATE {$this->table} 
                   SET hora_salida = :hora_salida,
                       ubicacion_salida = :ubicacion_salida
                   WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $data['id'],
                'hora_salida' => $data['hora_salida'],
                'ubicacion_salida' => $data['ubicacion_salida'] ?? null
            ]);

        } catch (Exception $e) {
            error_log("Error en Attendance::update: " . $e->getMessage());
            throw new Exception("Error al actualizar asistencia");
        }
    }

    /**
     * Obtiene estadísticas de asistencia para el dashboard
     */
    public function getStats(string $startDate, string $endDate): array
    {
        try {
            $sql = "SELECT 
                       COUNT(*) as total,
                       SUM(CASE WHEN estado = 'puntual' THEN 1 ELSE 0 END) as puntual,
                       SUM(CASE WHEN estado = 'tardanza' THEN 1 ELSE 0 END) as tardanza
                   FROM {$this->table}
                   WHERE DATE(hora_entrada) BETWEEN :start_date AND :end_date";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en Attendance::getStats: " . $e->getMessage());
            throw new Exception("Error al obtener estadísticas");
        }
    }

    /**
     * Obtiene la asistencia actual de un empleado
     */
    public function getCurrentDayAttendance(int $employeeId): ?array
    {
        try {
            $sql = "SELECT * FROM {$this->table}
                   WHERE empleado_id = :empleado_id
                   AND DATE(hora_entrada) = CURRENT_DATE
                   LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['empleado_id' => $employeeId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;

        } catch (Exception $e) {
            error_log("Error en Attendance::getCurrentDayAttendance: " . $e->getMessage());
            throw new Exception("Error al obtener asistencia actual");
        }
    }
}