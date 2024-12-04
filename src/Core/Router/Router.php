<?php

namespace Core\Router;

use Core\Database\Database;
use Core\Request\Request;
use Core\Response\Response;

class Router
{
    public $route_to_execute = null;
    public $module_to_execute = '';
    public const  INIT_POINT = '/';

    static $ctx = null;
    public function __construct(
        private Request $request,
        private Database|null $database = null,
        private array $routes = [],
        private array $no_query_routes = [],
        private array $query_routes = [],
        private array $route_quries = [],
        private array $con = [],
        private $dto = null
    ) {
        self::$ctx = $con;
    }

    public function get_query_routes()
    {
        return $this->query_routes;
    }

    public function get_no_query_routes()
    {
        return $this->no_query_routes;
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

    public function get_queries()
    {
        return $this->route_quries;
    }

    public function getDto()
    {
        $body = $this->get_request_body();

        $dtoClass = $this->dto;

        if (!$dtoClass) return null;

        return new $dtoClass($body);
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

    public function prepare_execution()
    {
        $route_to_execute = $this->get_route_to_execute();

        if (!$route_to_execute) return Response::set_http_response_code(404);

        $this->define_route_and_module($this->routes, $route_to_execute);
    }

    public function get_route_to_execute()
    {
        $no_query_routes = $this->get_no_query_routes();

        $path = $this->request->get_path();

        $route_exists =  $no_query_routes[$path] ?? false;

        if ($route_exists) {
            return [$path => $route_exists];
        }

        $query_routes = $this->get_query_routes();
        $split_path = explode('/', $path);
        $split_path_first_element = count($split_path) === 2 ? '' : $split_path[1];
        $split_path_last_element = $split_path[count($split_path) - 1];

        foreach ($query_routes as $key => $route) {

            $split_key = explode('/:', $key);

            $split_key_first_element = $split_key[0];

            if ($split_path_first_element === '') {
                $this->request->setParam($split_key[1], $split_path_last_element);
                return [$key => array_merge($route, ['query' => [$split_key[1] => $split_path_last_element]])];
            }

            if ('/' . $split_path_first_element === $split_key_first_element) {
                $this->request->setParam($split_key[1], $split_path_last_element);
                return [$key => array_merge($route, ['query' => [$split_key[1] => $split_path_last_element]])];
            }
        }
    }

    public function get_controller_instance()
    {
        $injectables = $this->module_to_execute::inject();

        $controller_to_execute = $this->module_to_execute::$controller;

        // ## check if database is in injectables
        // $array_ = array_values($injectables);
        // if (in_array('myDb', $array_)) {
        //     $injectableKey = array_search('myDb', $injectables);
        //     $injectables[$injectableKey] = $this->database;
        // }

        ## Construct Controller class
        return new $controller_to_execute(...$injectables);
    }

    public function create_route_ctx($configuration, $metadata)
    {
        ## We create APP context
        $context = [
            'configuration' => $configuration,
            'metadata' => $metadata,
            'request' => $this->request,
        ];
    }

    public function get_request_body()
    {
        $body = [];

        if ($this->request->get_method() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->request->get_method() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    public function get_controller_config()
    {
        if (method_exists($this->module_to_execute, 'config')) {
            return $this->module_to_execute::config();
        }

        return [];
    }


    static public function guard_route(string $route_method, $callback)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === $route_method) {
            return $callback();
        }
    }





    static function execute_route_with_params(string $path, string $route_method, $callback)
    {
        $url = $_SERVER['REQUEST_URI'];
        $query_param = explode('/', $url)[2];

        $route_to_execute = $callback($query_param);

        return $route_to_execute;
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
