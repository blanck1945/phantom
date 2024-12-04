<?php

namespace Core;

use Core\Cors\Cors;
use Core\Database\Database;
use Core\Metadata\Metadata;
use Core\Render\Render;
use Core\Router\Router;
use Core\Request\Request;
use Core\Server\Server;
use Core\View\View;

class Phantom
{
    private static string $ROOT_DIR;
    public $request;
    public $router;
    public $middlewares;
    public $metadata;
    public $configuration;
    public $guards;
    public $interceptors;
    public $method;
    public $path;
    private $router_handler;
    private $cors_handler;
    private $server_handler;
    private $render_handler;
    private $view_handler;
    private $metadata_handler;
    private $database;

    private $databaseReferenceName = 'database';

    public function __construct(private array $config = [])
    {
        self::$ROOT_DIR = dirname(__DIR__);
        $this->request = new Request();
        $this->database = Database::getInstance();
        $this->router_handler = new Router($this->request, $this->database);
        $this->cors_handler = new Cors();
        $this->server_handler = new Server($config['metadata'] ?? [], $this->request, $this->router_handler);
        $this->view_handler = new View(self::$ROOT_DIR . "/views");
        $this->render_handler = new Render($this->view_handler);
        $this->metadata_handler = new Metadata($config['metadata'] ?? []);
        $this->configuration = $config['configuration'] ?? [];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
    }

    public function boostrap()
    {
        $this->router_handler->prepare_execution();

        $route_to_execute = $this->router_handler->get_route_to_execute();

        $route_to_execute = $this->router_handler->route_exists();

        $this->server_handler->valid_route_guard($route_to_execute);

        $instance = $this->router_handler->get_controller_instance();

        $queries = $this->router_handler->get_queries();

        $executable = $this->server_handler->execute_handler($route_to_execute, $instance, $queries);

        $this->server_handler->set_json_header($executable);

        $route_config = $this->router_handler->get_controller_config();

        if (isset($route_config['metadata'])) {
            if (isset($route_config['metadata']['global']) && $route_config['metadata']['global'] === false) {
                $executable['metadata'] = [
                    'css' => [],
                    'js' => [],
                    'favicon' => ''
                ];
            } else {
                $executable = $this->metadata_handler->merge_metadata(array_merge($route_config, $executable));
            }
        } else {
            $global_metadata = $this->metadata_handler->get_global_metadata();
            $executable['metadata'] = $global_metadata;
        }

        $view = $this->view_handler->get_view($executable['view'] ?? null);

        $this->render_handler->render($executable, $route_config, $view);
    }

    public function set_configuration($error_page = [], $port = 3000)
    {
        $this->configuration['404'] = $error_page;
        $this->configuration['port'] = $port;


        // if (!is_null($database)) {
        //     $this->configuration['database'] = new Database($database['driver']);
        // }
    }


    /**
     * Root method to register routes 
     * Routes are registers in separate arrays depending on if the have query params or not
     * 
     * @param array $routes
     * @return void
     */
    public function register_routes(...$routes)
    {
        $this->router_handler->register($routes);
    }

    public function set_cors()
    {
        $this->cors_handler->enable_cors();
    }

    public function set_metadata($css = [], $js = [], $favicon = "")
    {
        $this->metadata_handler->add_metadata([
            "css" => $css,
            "js" => $js,
            "favicon" => $favicon
        ]);
    }

    public function set_middlewares($middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    public function set_guards($guards = [])
    {
        $this->guards = $guards;
    }

    public function set_interceptor($interceptors = [])
    {
        $this->interceptors = $interceptors;
    }


    public function get_root_path()
    {
        return self::$ROOT_DIR;
    }

    public function get_view_path()
    {
        return self::$ROOT_DIR . "/views";
    }

    public function setDb(string $connectionString, string $user, string $password, string $databaseReference = 'database')
    {
        // $instance = Database::getInstance();

        // $instance->connect(
        //     $connectionString,
        //     $user,
        //     $password,
        // );


        // $this->databaseReferenceName = $databaseReference;
    }

    public function testDbConnection()
    {
        return $this->database->testConnection();
    }

    public function injectService(string $serviceName, $service)
    {
        return [
            $serviceName => $this->$service
        ];
    }
}
