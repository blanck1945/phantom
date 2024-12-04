<?php

namespace Core\Database;

use Core\Autoload\AutoLoad;
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
    protected function __construct()
    {
        AutoLoad::loadAutoload();
        $this->db_host = $_ENV['DB_HOST'];
        $this->db_port = $_ENV['DB_PORT'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->db_user = $_ENV['DB_USER'];
        $this->db_password = $_ENV['DB_PASSWORD'];
        $this->connect_db();
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
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }
    // if (!isset(self::$db)) {
    //     return self::connect();
    // } else {
    //     return self::$db;
    // }

    public static function connect(): PDO | null
    {
        $db_user = $_ENV['DB_USER'];
        $db_password = $_ENV['DB_PASSWORD'];
        $connection_string = $_ENV['DB_CONNECTION_STRING'];

        // $connection_string = "host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_password";

        try {
            return self::$db = new PDO($connection_string, $db_user, $db_password);
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function testConnection()
    {
        var_dump($this->db->getAttribute(PDO::ATTR_CONNECTION_STATUS));
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
}
