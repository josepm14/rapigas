<?php

namespace App\Models;

use PDO;
use Exception;

class Employee extends BaseModel
{
    protected string $table = 'empleados';

    /**
     * Obtiene empleados con filtros opcionales
     */
    public function getAll(array $filters = []): array 
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];

            if (!empty($filters['departamento'])) {
                $sql .= " AND departamento = :departamento";
                $params['departamento'] = $filters['departamento'];
            }

            if (!empty($filters['estado'])) {
                $sql .= " AND estado = :estado";
                $params['estado'] = $filters['estado'];
            }

            if (!empty($filters['buscar'])) {
                $sql .= " AND (nombres LIKE :buscar OR apellidos LIKE :buscar OR dni LIKE :buscar)";
                $params['buscar'] = "%{$filters['buscar']}%";
            }

            $sql .= " ORDER BY apellidos, nombres";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en Employee::getAll: " . $e->getMessage());
            throw new Exception("Error al obtener empleados");
        }
    }

    /**
     * Busca empleado por DNI
     */
    public function findByDNI(string $dni): ?array 
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE dni = :dni");
            $stmt->execute(['dni' => $dni]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;

        } catch (Exception $e) {
            error_log("Error en Employee::findByDNI: " . $e->getMessage());
            throw new Exception("Error al buscar empleado por DNI");
        }
    }

    /**
     * Cuenta empleados por estado
     */
    public function countByStatus(string $status): int 
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE estado = :estado");
            $stmt->execute(['estado' => $status]);
            
            return (int)$stmt->fetchColumn();

        } catch (Exception $e) {
            error_log("Error en Employee::countByStatus: " . $e->getMessage());
            throw new Exception("Error al contar empleados por estado");
        }
    }

    /**
     * Obtiene empleados en vacaciones o permiso
     */
    public function countOnLeave(): int 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM {$this->table} 
                WHERE estado IN ('vacaciones', 'permiso')
            ");
            $stmt->execute();
            
            return (int)$stmt->fetchColumn();

        } catch (Exception $e) {
            error_log("Error en Employee::countOnLeave: " . $e->getMessage());
            throw new Exception("Error al contar empleados en permiso");
        }
    }

    /**
     * Obtiene estadísticas de permisos y vacaciones
     */
    public function getLeaveStats(): array 
    {
        try {
            $sql = "
                SELECT estado, COUNT(*) as total
                FROM {$this->table}
                WHERE estado IN ('vacaciones', 'permiso')
                GROUP BY estado
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en Employee::getLeaveStats: " . $e->getMessage());
            throw new Exception("Error al obtener estadísticas de permisos");
        }
    }

    /**
     * Verifica si existe un empleado con el mismo DNI
     */
    public function isDNIDuplicate(string $dni, ?int $excludeId = null): bool 
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE dni = :dni";
            $params = ['dni' => $dni];

            if ($excludeId) {
                $sql .= " AND id != :id";
                $params['id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return (int)$stmt->fetchColumn() > 0;

        } catch (Exception $e) {
            error_log("Error en Employee::isDNIDuplicate: " . $e->getMessage());
            throw new Exception("Error al verificar DNI duplicado");
        }
    }
}