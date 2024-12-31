<?php

namespace Core\Database;

use Exception;
use PDO;

class DatabaseHelpers
{
    public function handle_select_query(string $table, string|array $columns)
    {
        if ($columns !== ' * ') {
            $columns = implode(', ', $columns);
        }

        $query = "SELECT $columns FROM $table WHERE ";

        return $query;
    }

    public function handle_query_params(string $query, array $where)
    {
        // Construir la consulta con parámetros
        $clauses = [];
        foreach ($where as $column => $value) {
            $clauses[] = "$column = :$column";
        }
        $query .= implode(' AND ', $clauses);

        return $query;
    }

    public function handle_PDO_params($stmt, array $where)
    {
        // Asignar los valores con el tipo correcto
        foreach ($where as $column => $value) {
            $paramType = $this->get_PDO_param_type($value);
            $stmt->bindValue(":$column", $value, $paramType);
        }
    }

    public function get_PDO_param_type($value): int
    {
        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } elseif (is_int($value)) {
            return PDO::PARAM_INT;
        } else {
            return PDO::PARAM_STR; // Por defecto, se considera string
        }
    }

    public function execute_query($db, $query, $where)
    {
        try {
            $stmt = $db->prepare($query);
            foreach ($where as $column => $value) {
                $paramType = $this->get_PDO_param_type($value);
                $stmt->bindValue(":$column", $value, $paramType);
            }
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}
