<?php

namespace Core\Database;

use Core\Autoload\AutoLoad;
use Core\Env\Env;
use Exception;
use PDO;

class Database
{

    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static $instances = [];
    private static Database|null $instance = null;

    private static PDO $db;

    private string $db_host;
    private string $db_name;

    private string $db_user;
    private string $db_password;
    private string $db_port;

    //     public function __construct(private string $db_type)
    //     {
    //         AutoLoad::loadAutoload();
    //         $this->db_type = $db_type;
    //         $this->db_host = $_ENV['DB_HOST'];
    //         $this->db_port = $_ENV['DB_PORT'];
    //         $this->db_name = $_ENV['DB_NAME'];
    //         $this->db_user = $_ENV['DB_USER'];
    //         $this->db_password = $_ENV['DB_PASSWORD'];
    //     }

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    private function __construct(private DatabaseHelpers $databaseHelpers)
    {
        $this->db_host = Env::get('DB_HOST');
        $this->db_port =  Env::get('DB_PORT');
        $this->db_name = Env::get('DB_NAME');
        $this->db_user = Env::get('DB_USER');
        $this->db_password = Env::get('DB_PASSWORD');
    }

    public function connect_db()
    {
        $connection_string = "host=$this->db_host port=$this->db_port dbname=$this->db_name user=$this->db_user password=$this->db_password";

        // try {
        //     return pg_connect($connection_string);
        // } catch (\Exception $e) {
        //     echo $e->getMessage();
        // }
    }

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
            self::$instance = new static(new DatabaseHelpers());
        }

        return self::$instance;
    }
    // if (!isset(self::$db)) {
    //     return self::connect();
    // } else {
    //     return self::$db;
    // }

    public static function connect(string $DB_USER, string $DB_PASSWORD, string $CONNECTION_STRING): void
    {
        // $db_user = $_ENV['DB_USER'];
        // $db_password = $_ENV['DB_PASSWORD'];
        // $connection_string = $_ENV['DB_CONNECTION_STRING'];

        // $connection_string = "host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_password";


        try {
            // $db = new PDO('pgsql:host=postgres;port=5432;dbname=kanas', $DB_USER, $DB_PASSWORD);
            // Establecer la conexión
            $db = new PDO("pgsql:host=postgres;port=5432;dbname=kanas;", "admin", "1234567", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            self::$db = $db;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function testConnection(): void
    {
        var_dump(self::$db->getAttribute(PDO::ATTR_CONNECTION_STATUS));
    }

    public function insert() {}

    //     private string $db_host;
    //     private string $db_name;

    //     private string $db_user;
    //     private string $db_password;
    //     private string $db_port;

    //     public function __construct(private string $db_type)
    //     {
    //         AutoLoad::loadAutoload();
    //         $this->db_type = $db_type;
    //         $this->db_host = $_ENV['DB_HOST'];
    //         $this->db_port = $_ENV['DB_PORT'];
    //         $this->db_name = $_ENV['DB_NAME'];
    //         $this->db_user = $_ENV['DB_USER'];
    //         $this->db_password = $_ENV['DB_PASSWORD'];
    //     }

    //     public function connect_db()
    //     {
    //         $connection_string = "host=$this->db_host port=$this->db_port dbname=$this->db_name user=$this->db_user password=$this->db_password";

    //         try {
    //             return pg_connect($connection_string);
    //         } catch (\Exception $e) {
    //             echo $e->getMessage();
    //         }
    //     }

    //     public function execute_query($query)
    //     {
    //         $connection = $this->connect_db();

    //         try {
    //             $result = pg_query($connection, $query);

    //             return $result;
    //         } catch (\Exception $e) {
    //             echo $e->getMessage();
    //         }
    //     }

    //     /**
    //      * @param string $tableName
    //      * @param array $columns
    //      * @param array $values
    //      * @return mixed
    //      */
    //     function insert($tableName, $columns, $values)
    //     {
    //         $query = "INSERT INTO $tableName $columns VALUES $values";
    //     }

    //     public function create($table, $data)
    //     {


    //         $connection = $this->connect_db();

    //         try {
    //             $sql_query = "INSERT INTO $table (
    //                 name,
    //                 description,
    //                 price,
    //                 quantity
    //             ) VALUES (
    //                 '$data->',
    //                 '$data->description',
    //                 $data->price,
    //                 $data->quantity
    //             )";
    //             $result = pg_query($connection, $sql_query);

    //             return $result;
    //         } catch (\Exception $e) {
    //             echo $e->getMessage();
    //         }
    //     }
    // }

    public function findAll(string $table)
    {
        try {

            $query = "SELECT * FROM $table";
            $stmt = self::$db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function findOne(string $table, array $where, string|array $columns = ' * ')
    {
        $query = $this->databaseHelpers->handle_select_query($table, $columns);

        $query = $this->databaseHelpers->handle_query_params($query, $where);

        return $this->execute_query($query, $where);
    }

    public function execute_query($query, $where)
    {
        try {
            $stmt = self::$db->prepare($query);
            foreach ($where as $column => $value) {
                $paramType = $this->databaseHelpers->get_PDO_param_type($value);
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
