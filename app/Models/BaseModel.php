<?php

namespace App\Models;

use PDO;
use Exception;

/**
 * Modelo Base
 * Proporciona funcionalidad común para todos los modelos
 */
abstract class BaseModel 
{
    /** @var PDO Conexión a la base de datos */
    protected PDO $db;
    
    /** @var string Nombre de la tabla */
    protected string $table;

    /**
     * Constructor
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    /**
     * Encuentra un registro por su ID
     */
    public function findById(int $id): ?array 
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
            
        } catch (Exception $e) {
            error_log("Error en {$this->table}::findById: " . $e->getMessage());
            throw new Exception("Error al buscar registro");
        }
    }

    /**
     * Encuentra registros por condiciones
     */
    public function findBy(array $conditions): array 
    {
        try {
            $whereClauses = [];
            $params = [];

            foreach ($conditions as $field => $value) {
                $whereClauses[] = "$field = :$field";
                $params[$field] = $value;
            }

            $whereString = implode(' AND ', $whereClauses);
            $sql = "SELECT * FROM {$this->table} WHERE $whereString";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en {$this->table}::findBy: " . $e->getMessage());
            throw new Exception("Error al buscar registros");
        }
    }

    /**
     * Crea un nuevo registro
     */
    public function create(array $data): int 
    {
        try {
            $fields = array_keys($data);
            $values = array_map(fn($field) => ":$field", $fields);

            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $this->table,
                implode(', ', $fields),
                implode(', ', $values)
            );

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            
            return (int)$this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error en {$this->table}::create: " . $e->getMessage());
            throw new Exception("Error al crear registro");
        }
    }

    /**
     * Actualiza un registro existente
     */
    public function update(array $data): bool 
    {
        try {
            if (!isset($data['id'])) {
                throw new Exception("ID requerido para actualización");
            }

            $id = $data['id'];
            unset($data['id']);

            $setClauses = array_map(
                fn($field) => "$field = :$field",
                array_keys($data)
            );

            $sql = sprintf(
                "UPDATE %s SET %s WHERE id = :id",
                $this->table,
                implode(', ', $setClauses)
            );

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([...$data, 'id' => $id]);
            
        } catch (Exception $e) {
            error_log("Error en {$this->table}::update: " . $e->getMessage());
            throw new Exception("Error al actualizar registro");
        }
    }

    /**
     * Elimina un registro por su ID
     */
    public function delete(int $id): bool 
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            return $stmt->execute(['id' => $id]);
            
        } catch (Exception $e) {
            error_log("Error en {$this->table}::delete: " . $e->getMessage());
            throw new Exception("Error al eliminar registro");
        }
    }

    /**
     * Cuenta el número total de registros
     */
    public function count(): int 
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
            return (int)$stmt->fetchColumn();
            
        } catch (Exception $e) {
            error_log("Error en {$this->table}::count: " . $e->getMessage());
            throw new Exception("Error al contar registros");
        }
    }
}