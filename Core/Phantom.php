<?php

namespace Core;

use config\BindingsConfig;
use Core\Cache\PhantomCache;
use Core\Cors\Cors;
use Core\Database\Database;
use Core\Env\Env;
use Core\Metadata\Metadata;
use Core\Render\Render;
use Core\Request\PhantomRequest;
use Core\Router\Router;
use Core\Server\Server;
use Core\View\View;
use DI\Container;

class Phantom
{
    public $request;
    public $router;
    public $middlewares;
    public $metadata;
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
    private PhantomCache $cache_handler;
    private $database;
    private $container;

    public function __construct(private array $config = [])
    {
        $this->container = new Container(BindingsConfig::get_config());
        $this->request = new PhantomRequest();
        $this->database = Database::getInstance();
        $this->router_handler = new Router($this->request, $this->database);
        $this->cors_handler = new Cors();
        $this->server_handler = new Server($config['metadata'] ?? [], $this->request, $this->router_handler);
        $this->view_handler = new View();
        $this->render_handler = new Render($this->view_handler);
        $this->metadata_handler = new Metadata($config['metadata'] ?? []);
        $this->cache_handler = new PhantomCache();
    }


    public function boostrap()
    {
        $this->cache_handler->read_cache_file($this->request->get_path(), $this->render_handler);

        //$this->router_handler->check_if_we_should_execute_route($start);

        $this->show_404_if_empty();

        $this->router_handler->execute_global_middlewares();

        $this->router_handler->execute_route_middleware();

        $this->router_handler->execute_guards();

        $this->router_handler->execute_pipes();

        $route_has_dto = $this->router_handler->check_if_route_has_dto();

        if ($route_has_dto) {
            $this->router_handler->execute_dto();
        }

        $instance = $this->router_handler->get_controller_instance($this->container);

        $queries = $this->router_handler->get_queries();

        $executable = $this->server_handler->execute_handler($instance, $queries);

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
            $executable['metadata'] = [
                ...$global_metadata,
                'css' => [],
                'js' => [],
                'favicon' => ''
            ];
        }

        if (Env::get('DEV_MODE') === 'false' && array_key_exists('view', $executable)) {
            $this->cache_handler->write_cache_file($this->request->get_path(), $executable['view'] ?? null);
        }

        $view = $this->view_handler->get_view($executable['view'] ?? null);

        $this->render_handler->render($executable, $route_config, $view);
    }

    public function show_404_if_empty()
    {
        if (empty($this->router_handler->get_module_to_execute())) {
            $route_config = $this->router_handler->get_controller_config();

            $view = $this->view_handler->get_view('404.blade.php');

            $this->render_handler->render([], $route_config, $view);
            exit;
        }
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

    /**
     * Root method to register routes 
     * Routes are registers in separate arrays depending on if the have query params or not
     * 
     * @param array $routes
     * @return void
     */
    public function register_routes_map(...$routes)
    {
        $this->router_handler->register_routes($routes);
    }

    /**
     * Enable CORS
     * 
     * @return void
     */
    public function set_cors(): void
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
        $this->router_handler->set_global_middlewares($middlewares);
    }

    public function set_guards($guards = [])
    {
        $this->guards = $guards;
    }

    public function set_interceptor($interceptors = [])
    {
        $this->interceptors = $interceptors;
    }

    public function setDb(string $driver = 'pgsql')
    {
        $this->database->initDb($driver);
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

    /**
     * Get Phantom Router
     * 
     * @return Router
     */
    public function get_router(): Router
    {
        return $this->router_handler;
    }
}
