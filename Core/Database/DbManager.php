<?php

declare(strict_types=1);

namespace Core\Database;


use Core\Env\Env;
use PDO;

class DbManager
{
    private $pdo;

    private string $query = "";

    private string $sqlToExecute = "";

    public function __construct()
    {
        $this->pdo = new PDO(
            Env::get('DB_CONNECTION_STRING'),
            Env::get('DB_USER'),
            Env::get('DB_PASSWORD')
        );
    }

    public function createTable($table, $exec)
    {
        if (!empty($this->query)) {
            $this->query = "";
        }

        try {
            $exec();

            $sql = $this->createStatment($table);

            $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage() . "\n";
            echo "SQL: " .  $sql . "\n";
        }
    }

    public function dropTable($table)
    {
        try {
            $this->sqlToExecute = $this->sqlToExecute . "DROP TABLE IF EXISTS $table;";

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

    /*
    |--------------------------------------------------------------------------
    |   Database actions
    |--------------------------------------------------------------------------
    |
    |
    */

    public function createStatment($table)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $table (" . $this->query . ")";
        return $sql;
    }

    /*
    |--------------------------------------------------------------------------
    |   Columns Types
    |--------------------------------------------------------------------------
    |
    |   Define the types of the columns
    |
    */

    public function id(string $primary = 'id'): static
    {
        if (empty($this->query))
            $this->query .= "$primary SERIAL PRIMARY KEY";
        else
            $this->query .= ", $primary SERIAL PRIMARY KEY";

        return $this;
    }

    public function string(string $name, string $length = '255')
    {

        if (empty($this->query))
            $this->query .= " $name VARCHAR($length)";
        else
            $this->query .= ',' . " $name VARCHAR($length)";

        return $this;
    }

    public function integer($name)
    {
        $this->query .= ", $name INT";

        return $this;
    }

    public function decimal($name, $length = 10, $precision = 2)
    {
        $this->query .= ", $name DECIMAL($length, $precision)";

        return $this;
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
        $this->query .= ", $name DATE";

        return $this;
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

        $this->pdo->exec($enum);

        return "$name $name NOT NULL";
    }

    public function timestamps()
    {
        $this->query .= ", created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        deleted_at TIMESTAMP";

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    |   Modifiers
    |--------------------------------------------------------------------------
    |
    |   Define the modifiers for the fields
    |
    */

    public function notNull(): static
    {
        $this->query .= " NOT NULL";

        return $this;
    }

    public function unique(): static
    {
        $this->query .= " UNIQUE";

        return $this;
    }


    /*
    |--------------------------------------------------------------------------
    |   Relationships
    |--------------------------------------------------------------------------
    |
    |   Define the relationships between tables
    |
    */

    public function hasOne(string $table, string $field = 'id'): static
    {
        $this->query .= ", FOREIGN KEY ($field) REFERENCES $table (id) ON DELETE CASCADE";

        return $this;
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
