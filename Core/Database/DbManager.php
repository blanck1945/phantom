<?php

declare(strict_types=1);

namespace Core\Database;


use Core\Env\Env;
use PDO;

class DbManager
{
    private $pdo;

    private string $sqlToExecute = "";

    public function __construct()
    {
        var_dump("llamando al constructor");
        $this->pdo = new PDO(
            Env::get('DB_CONNECTION_STRING'),
            Env::get('DB_USER'),
            Env::get('DB_PASSWORD')
        );
    }

    public function createTable($table, $fields)
    {
        try {
            $sql = $this->build_sql($table, $fields);

            $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function dropTable($table)
    {
        try {
            $this->sqlToExecute = $this->sqlToExecute . "DROP TABLE IF EXISTS $table;";

            var_dump($this->sqlToExecute);

            $this->pdo->exec($this->sqlToExecute);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function dropEnum($enum)
    {
        try {
            $this->sqlToExecute = $this->sqlToExecute . "DROP TYPE IF EXISTS $enum;\n";
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insert(string $table, array $data)
    {
        try {
            $fields = implode(", ", array_keys($data));
            $values = implode("', '", array_values($data));
            $sql = "INSERT INTO $table ($fields) VALUES ('$values')";
            $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function build_sql($table, $fields)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $table (";
        foreach ($fields as $field) {
            $sql .= $field . ",";
        }
        $sql = rtrim($sql, ",");
        $sql .= ")";
        return $sql;
    }

    public function primaryKey($name)
    {
        return "$name SERIAL PRIMARY KEY";
    }

    public function string(string $name, string $length = '255'): string
    {
        return "$name VARCHAR($length)";
    }

    public function integer($name)
    {
        return "$name INT";
    }

    public function decimal($name, $length, $precision)
    {
        return "$name DECIMAL($length, $precision)";
    }

    public function text($name)
    {
        return "$name TEXT";
    }

    public function timestamp($name)
    {
        return "$name TIMESTAMP";
    }

    public function date($name)
    {
        return "$name DATE";
    }

    public function time($name)
    {
        return "$name TIME";
    }

    public function boolean($name)
    {
        return "$name BOOLEAN";
    }

    public function enum(string $name, array $values): string
    {
        $drop = "DROP TYPE IF EXISTS $name";

        $this->pdo->exec($drop);

        $enum = "CREATE TYPE $name AS ENUM (" . "'" . implode("', '", $values) . "'" . ")";

        var_dump($enum);

        $this->pdo->exec($enum);

        return "$name $name NOT NULL";
    }

    public function hasOne(string $table, string $field = 'id')
    {
        return "FOREIGN KEY ($field) REFERENCES $table (id) ON DELETE CASCADE";
    }

    public function hasMany(string $table, string $field = 'id')
    {
        return "FOREIGN KEY ($field) REFERENCES $table (id) ON DELETE CASCADE";
    }

    public function belongsTo(string $table, string $field = 'id')
    {
        return "FOREIGN KEY ($field) REFERENCES $table (id) ON DELETE CASCADE";
    }
}
