<?php

namespace Core\Database;

use config\DatabaseConfig;
use Exception;
use PDO;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{

    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static Database|null $instance = null;

    private static PDO $db;
    private Capsule $capsule;

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    public function __construct(private DatabaseActions $databaseActions) {}

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone() {}

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     */
    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(new DatabaseActions(new DatabaseHelpers()));
        }

        return self::$instance;
    }

    public function testConnection(): bool
    {
        try {
            // Ejecutar una consulta básica para verificar la conexión
            Capsule::connection()->getPdo();
            return true; // Conexión exitosa
        } catch (\PDOException $e) {
            // Manejar el error si no se puede conectar
            echo "Error de conexión: " . $e->getMessage();
            return false;
        }
    }

    public function testCapsule(): bool
    {
        try {
            // Ejecutar una consulta básica para verificar la conexión
            Capsule::connection()->getPdo();
            return true; // Conexión exitosa
        } catch (\PDOException $e) {
            // Manejar el error si no se puede conectar
            echo "Error de conexión: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Initialize the database connection - Singleton
     * 
     * @param string $driver 'pgsql' | 'mysql' | 'sqlite'
     * @return void
     */
    public function initDb(string $driver = 'pgsql')
    {
        try {
            if (!isset(self::$instance)) {
                self::$instance = new static(new DatabaseActions(new DatabaseHelpers()));
            }

            // Instancia de Eloquent Capsule
            $capsule = new Capsule();

            // Agregar conexión
            $capsule->addConnection(DatabaseConfig::getDatabaseConfig()[$driver]);

            // Iniciar Eloquent
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            $this->capsule = $capsule;
        } catch (Exception $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage();
        }
    }

    public function findAll(string $table)
    {
        return $this->databaseActions->findAll(self::$db, $table);
    }

    public function findOne(string $table, array $where, string|array $columns = ' * ')
    {
        return $this->databaseActions->findOne(self::$db, $table, $where, $columns);
    }
}
