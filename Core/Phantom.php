<?php

namespace Core;

use config\AuthConfig;
use config\BindingsConfig;
use config\GlobalVariables;
use Core\Cache\PhantomCache;
use Core\Cors\Cors;
use Core\Database\Database;
use Core\Metadata\Metadata;
use Core\Render\PhantomRender;
use Core\Request\PhantomRequest;
use Core\Response\PhantomResponse;
use Core\Router\Router;
use Core\Server\Server;
use Core\Services\AuthService\AuthService;
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
        $this->cors_handler = new Cors();
        $this->view_handler = new View();
        $this->metadata_handler = new Metadata($config['metadata'] ?? []);
        $this->cache_handler = new PhantomCache();
        $this->render_handler = new PhantomRender(
            $this->metadata_handler,
            $this->cache_handler,
            $this->request,
            $this->view_handler,
            $this->container->get(GlobalVariables::class)
        );
        $this->router_handler = new Router(
            $this->request,
            $this->database,
            $this->render_handler
        );
        $this->server_handler = new Server(
            $config['metadata'] ?? [],
            $this->request,
            $this->router_handler
        );
    }


    public function boostrap()
    {
        session_start();

        $this->cache_handler->read_cache_file($this->request->get_path(), $this->render_handler);

        //$this->router_handler->check_if_we_should_execute_route($start);

        $this->show_404_if_empty();

        $this->router_handler->execute_global_middlewares($this->container);

        $this->router_handler->execute_route_middleware();

        $this->router_handler->execute_guards($this->container);

        $this->router_handler->execute_pipes();

        $this->router_handler->execute_validator();

        $this->router_handler->execute_dto();

        $instance = $this->router_handler->get_controller_instance($this->container);

        $queries = $this->router_handler->get_queries();

        $executable = $this->server_handler->execute_handler($instance, $queries);

        if (array_key_exists('redirect', $executable)) {
            PhantomResponse::redirect($executable['redirect']);
            exit;
        }

        $this->server_handler->set_json_header($executable);

        $route_config = $this->router_handler->get_controller_config();

        $this->render_handler->handle_view($executable,   $route_config);

        session_write_close();
    }


    public function show_404_if_empty()
    {
        if (empty($this->router_handler->get_phantom_handler()->get_module())) {
            $route_config = $this->router_handler->get_controller_config();

            $this->render_handler->render([], $route_config, '404.blade.php');
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

    /**
     * Set auth strategy
     * 
     * @return void
     */
    public function setAuthStrategy(): void
    {
        AuthService::setAuthStrategy(AuthConfig::getAuthConfig());
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
