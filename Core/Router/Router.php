<?php

namespace Core\Router;

use Core\Database\Database;
use Core\Exception\ViewException;
use Core\Helpers\PhantomValidator;
use Core\Request\PhantomRequest;
use DI\Container;
use Exception;

class Router
{
    public const  INIT_POINT = '/';
    public $route_to_execute = null;
    private $handler = null;

    private ViewException $viewException;
    public $module_to_execute = '';


    static $ctx = null;
    public function __construct(
        private PhantomRequest $request,
        private Database|null $database = null,
        private array $routes = [],
        private array $no_query_routes = [],
        private array $global_middlewares = [],
        private array $route_middlewares = [],
        private array $query_routes = [],
        private array $route_quries = [],
        private array $route_guards = [],
        private array $route_pipes = [],
        private string $route_dto = '',
        private array $con = [],
        private string|null $dto = null
    ) {
        self::$ctx = $con;
        $this->viewException = new ViewException();
    }

    public function set_routes($routes)
    {
        $this->routes = $routes;
    }

    public function set_no_queries_routes($routes)
    {
        $this->no_query_routes = $routes;
    }

    public function set_query_routes($routes)
    {
        $this->query_routes = $routes;
    }

    /**
     * @param array $middlewares
     * 
     * @return void
     */
    public function set_global_middlewares(array $middlewares): void
    {
        $this->global_middlewares = $middlewares;
    }

    /*********************************************************************
     * 
     *************************** GETTERS *********************************
     * 
     ********************************************************************/

    public function get_queries()
    {
        return $this->route_quries;
    }

    public function getDto(): string
    {
        return $this->route_dto;
    }


    public function get_query_routes()
    {
        return $this->query_routes;
    }

    public function get_no_query_routes()
    {
        return $this->no_query_routes;
    }

    /**
     * Global middlewares getter
     * 
     * @return array
     */
    public function get_global_middlewares(): array
    {
        return $this->global_middlewares;
    }

    /**
     * Middlewares getter
     * 
     * @return array
     */
    public function get_middlewares(): array
    {
        return $this->route_middlewares;
    }

    /**
     * Guards getter
     * 
     * @return array
     */
    public function get_guards(): array
    {
        return $this->route_guards;
    }

    public function get_handler()
    {
        return $this->handler;
    }

    /**
     * Get module to execute
     * 
     * @return string
     */
    public function get_module_to_execute(): string
    {
        return $this->module_to_execute;
    }

    /*********************************************************************
     * 
     *********************************************************************
     * 
     ********************************************************************/

    //---------------------------------------------------------------------

    /*********************************************************************
     * 
     *************************** EXECUTERS *******************************
     * 
     ********************************************************************/

    /**
     * Execute route middlewares
     * 
     * @return void
     */
    public function execute_global_middlewares()
    {
        $middlewares = $this->get_global_middlewares();

        if (empty($middlewares)) return;

        foreach ($middlewares as $middleware) {
            $exec = new $middleware();
            $exec->handler();
        }
    }

    public function execute_route_middleware()
    {
        $middlewares = $this->get_middlewares();

        if (empty($middlewares)) return;

        foreach ($middlewares as $middleware) {
            $middleware->handler($this->request);
        }
    }

    /**
     * Execute route guards
     * 
     * @return void
     */
    public function execute_guards()
    {
        if (empty($this->get_guards())) return;

        $guards = $this->get_guards();

        foreach ($guards as $guard) {
            $exec = new $guard();
            $exec->handler();
        }
    }

    /**
     * Execute route dto
     * 
     * @return array
     */
    public function execute_dto(): array
    {
        $dto = $this->getDto();

        $body = $_POST;

        $dto_instance = new $dto(...$body);

        return $dto_instance->apply_validation();
    }

    /**
     * Summary params pipes
     * 
     * @return void
     */
    public function execute_pipes()
    {
        if (empty($this->route_pipes)) return;

        $pipes = $this->route_pipes;

        foreach ($pipes as $pipe) {
            $validator = new PhantomValidator($pipe['prop'], $pipe['pipes']);

            $validator->validate($this->route_quries);
        }
    }

    /*********************************************************************
     * 
     *********************************************************************
     * 
     ********************************************************************/

    public function check_if_route_has_dto()
    {
        return $this->getDto();
    }

    public function define_route_and_module($routes, $route_to_execute)
    {
        foreach ($routes as $module) {
            $route = $module::routes();

            $route_key = array_key_first($route_to_execute);

            $route_match = $route['routes'][$route_key] ?? [];


            $method = $this->request->get_method();

            if (key_exists($method, $route_match)) {
                $this->route_to_execute = $route_match[$method];
                $this->module_to_execute = $module;
                $this->dto = $route_match[$method]['dto'] ?? null;
                $this->route_quries = $this->route_to_execute[$route_key]['query'] ?? [];
            }
        }
    }


    public function register_routes($phantomRoutes)
    {
        $moduleRoutes = [];
        $route_to_call = null;
        $module = null;

        foreach ($phantomRoutes as $handler) {
            $newModule = new $handler();
            $moduleRoutes = $newModule::routes();

            if (array_key_exists($_SERVER['REQUEST_URI'], $moduleRoutes)) {
                $route_to_call = $moduleRoutes[$_SERVER['REQUEST_URI']];
                $module = $handler;
            } else {
                foreach ($moduleRoutes as $path => $routes) {

                    // Verificamos si la ruta tiene un parámetro dinámico con el formato /:{propiedad}
                    if (strpos($path, ':') !== false) {

                        // Convertimos el patrón en una expresión regular
                        $pattern = preg_replace('/:\w+/', '([^/]+)', $path);
                        $pattern = '#^' . $pattern . '$#';

                        $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Asegurarse de trabajar solo con el path

                        if (preg_match($pattern, $current_path, $matches)) {
                            $route_to_call = $moduleRoutes[$path];
                            $module = $handler;
                            $this->set_queries($path, $matches);
                        }
                    }
                };
            }
        };

        if (!$route_to_call) return $this->viewException->notFound();

        $method = $this->request->get_method();
        $executable = $route_to_call[$method];
        $this->set_handler($executable);
        $this->set_guards($executable);
        $this->set_pipes($executable);
        $this->set_middlewares($executable);

        $this->route_to_execute = [$_SERVER['REQUEST_URI'] => [$method => $route_to_call[$method]]];
        $this->module_to_execute = $module;

        if (
            $method === 'POST' &&
            is_array($route_to_call[$method]) &&
            array_key_exists('dto', $route_to_call[$method])
        ) {
            $this->route_dto = $route_to_call[$method]['dto'];
        }
    }

    /**
     * Set handler
     * 
     * @param array $handler
     * 
     * @return void
     */
    public function set_handler(array|string $handler)
    {
        $this->handler = is_array($handler) && array_key_exists('controller', $handler)
            ? $handler['controller']
            : $handler;
    }

    /**
     * Set guards
     * 
     * @param array $guards
     * 
     * @return void
     */
    public function set_guards(array|string $guards)
    {
        if (is_string($guards)) {
            $this->route_guards = [];
            return;
        }

        $this->route_guards = array_key_exists('guards', $guards)
            ? (array) $guards['guards']
            : [];
    }

    /**
     * Set pipes
     * 
     * @param array $pipes
     * 
     * @return void
     */
    public function set_pipes(array|string $pipes)
    {
        if (is_string($pipes)) {
            $this->route_pipes = [];
            return;
        }

        $this->route_pipes = array_key_exists('params', $pipes)
            ? $pipes['params']
            : [];
    }

    /**
     * Set middlewares
     * 
     * @param array $middlewares
     * 
     * @return void
     */
    public function set_middlewares(array|string $middlewares)
    {
        if (is_string($middlewares)) {
            $this->route_middlewares = [];
            return;
        }

        $this->route_middlewares = array_key_exists('middlewares', $middlewares)
            ? (array) $middlewares['middlewares']
            : [];
    }

    public function set_queries($path, $matches)
    {
        // split array witn the caracter / and remove the first element
        $split_path = explode('/', $path);
        array_shift($split_path);

        $queries = [];

        if (count($split_path) === 1) {
            $key = explode(':', $split_path[0]);
            $queries[$key[1]] = $matches[1];
        } else {
            for ($i = 0; $i < count($split_path); $i++) {
                $key = explode(':', $split_path[$i]);
                $queries[$key[1]] = $matches[$i];
            }
            array_shift($queries);
        }

        $this->route_quries = $queries;
    }

    /**
     * Root method to register routes
     * Set routes in the router class
     * Separate routes with query params from routes without query params
     * Set three properties in the router class 
     * 1. $routes
     * 2. $no_query_routes
     * 3. $query_routes
     * 
     * @param array $routes
     * 
     * @return void
     */
    public function register($routes)
    {
        $this->set_routes($routes);

        $all_routes = [];
        $no_query_routes = [];
        $query_routes = [];

        foreach ($routes as $module) {
            $route = $module::routes();

            $all_routes = array_merge($all_routes, $route['routes']);

            foreach ($all_routes as $key => $route) {
                if (str_contains($key, ':')) {
                    $query_routes = array_merge($query_routes, [$key => $route]);
                } else {
                    $no_query_routes = array_merge($no_query_routes, [$key => $route]);
                }
            }
        }

        $this->set_no_queries_routes($no_query_routes);
        $this->set_query_routes($query_routes);
    }

    public function get_controller_instance(Container $container)
    {
        if (empty($this->module_to_execute)) {
            return $this->viewException->notFound();
        }

        // $injectables = $this->module_to_execute::inject();
        // $dependencies = [];

        // try {
        //     foreach ($injectables as $key => $injectable) {
        //         $dependencies[] = $container->get($injectable);
        //     }
        // } catch (Exception $e) {
        //     echo $e->getMessage();
        // }

        $controller_to_execute = $this->module_to_execute::$controller;

        // ## check if database is in injectables
        // $array_ = array_values($injectables);
        // if (in_array('myDb', $array_)) {
        //     $injectableKey = array_search('myDb', $injectables);
        //     $injectables[$injectableKey] = $this->database;
        // }

        ## Construct Controller class
        return $container->get($controller_to_execute);
        // return new $controller_to_execute(...$dependencies);
    }

    public function get_controller_config()
    {
        if (method_exists($this->module_to_execute, 'config')) {
            return $this->module_to_execute::config();
        }

        return [];
    }

    static public function execute_route(string $path, string $route_method, $callback)
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $route_to_execute = [];

        if (str_contains($path, ':')) {
            $split_path = explode(':', $path);

            # Remove last character from split_path
            $split_path[0] = substr($split_path[0], 0, -1);

            if (str_contains($url, $split_path[0]) && $url !== $split_path[0]) {
                $query_param = explode('/', $url)[2];
                $route_to_execute = $callback($query_param);
            }
        } else {

            if ($url === $path && $method === $route_method) {
                $route_to_execute = $callback();
            }
        }

        return [
            'route' => $route_to_execute,
            'metadata' => $route_to_execute['metadata'] ?? [],
            'variables' => $route_to_execute['variables'] ?? null,
            'view' => $route_to_execute['view'] ?? null,
            'path' => $path,
            'method' => $route_method
        ];
    }

    public function show_routes()
    {
        var_dump($this->routes);
    }

    public function get_routes()
    {
        return $this->routes;
    }

    public function format_url(): string
    {
        $base = $this->request->get_path();
        if (str_contains($base, '?')) {
            $base = explode("?", $base)[0];
        }

        return $base;
    }

    public function route_exists()
    {
        return $this->route_to_execute;
    }

    public function get_page_data($route, $configuration)
    {
        if (empty($route)) {
            return $configuration['404'];
        } else {
            return $route;
        }
    }
}
