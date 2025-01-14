<?php

namespace Core\Router;

use config\BindingsConfig;
use config\PathConfig;
use Core\Database\Database;
use Core\Exception\ViewException;
use Core\Helpers\PhantomHandler;
use Core\Helpers\PhantomValidator;
use Core\Interfaces\ICoreController;
use Core\Render\PhantomRender;
use Core\Request\PhantomRequest;
use Core\Response\PhantomResponse;
use Core\Services\FormService\FormService;
use Core\Services\ValidatorService;
use Core\Ui\Forms\FormBuilder;
use DI\Container;

class Router
{
    public const  INIT_POINT = '/';
    public $route_to_execute = null;
    private $handler = null;

    private ViewException $viewException;
    public $module_to_execute = '';

    private PhantomHandler $phantomHandler;


    static $ctx = null;
    public function __construct(
        private PhantomRequest $request,
        private Database|null $database = null,
        private PhantomRender|null $render = null,
        private ValidatorService|null $validator = null,
        private string $csrf = '',
        private array $routes = [],
        private array $no_query_routes = [],
        private array $global_middlewares = [],
        private array $query_routes = [],
        private array $route_quries = [],
        private array $route_pipes = [],
        private string $route_dto = '',
        private string $path_to_excute = '',
        private array $con = [],
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

    public function setValidator($executable)
    {
        if (is_array($executable) && array_key_exists('validator', $executable)) {
            $validator = new $executable['validator'](new FormService(new FormBuilder()));
            $this->validator = new ValidatorService(
                $validator
            );
        }
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

    /**
     * 
     */
    public function get_validator(): ValidatorService|null
    {
        return $this->validator;
    }

    public function get_phantom_handler(): PhantomHandler
    {
        return $this->phantomHandler;
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
    public function execute_global_middlewares(Container $container)
    {
        $middlewares = $this->get_global_middlewares();

        if (empty($middlewares)) return;

        foreach ($middlewares as $middleware) {
            $exec = $container->get($middleware);
            $exec->handler();
        }
    }

    public function execute_route_middleware()
    {
        $middlewares = $this->phantomHandler->get_middlewares();

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
    public function execute_guards(Container $container)
    {
        $guards = $this->phantomHandler->get_guards();

        if (empty($guards)) {

            if (
                !PathConfig::publicPaths($this->path_to_excute) &&
                isset($_COOKIE['auth_token'])
            ) {
                PhantomResponse::redirect('/', 302);
            }

            return;
        }

        foreach ($guards as $guard) {
            $exec = $container->get($guard);
            $exec->handler();
        }
    }

    /**
     * Execute route validator
     * 
     * @return void
     */
    public function execute_validator()
    {
        $validator = $this->phantomHandler->get_validator();

        if ($validator === null) return;

        $body = $this->request->getBody();

        $errors = $validator->validate($body);

        if (!empty($errors)) {
            $this->render->handle_view($errors, $this->get_controller_config());
            exit;
        }
    }

    /**
     * Execute route dto
     * 
     * @return void
     */
    public function execute_dto(): void
    {
        $dto = $this->phantomHandler->get_dto();

        if (empty($dto)) return;

        $body = $this->request->getBody();

        if (array_key_exists('_token', $body)) {
            unset($body['_token']);
        }

        $dto_instance = new $dto(...$body);

        $this->request->setDto($dto_instance);
    }

    /**
     * Summary params pipes
     * 
     * @return void
     */
    public function execute_pipes()
    {
        $pipes = $this->phantomHandler->get_pipes();

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
                // $this->dto = $route_match[$method]['dto'] ?? null;
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
                $this->path_to_excute = $_SERVER['REQUEST_URI'];
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
                            $this->path_to_excute =  $path;
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

        $this->phantomHandler = new PhantomHandler(
            $module,
            [$_SERVER['REQUEST_URI'] => [$method => $route_to_call[$method]]],
            $executable['controller'] ?? $executable,
            $executable['guards'] ?? [],
            $executable['pipes'] ?? [],
            $executable['middlewares'] ?? [],
            $executable['csrf'] ?? false,
            $executable['validator'] ?? null,
            $executable['dto'] ?? null
        );

        $this->route_to_execute = [$_SERVER['REQUEST_URI'] => [$method => $route_to_call[$method]]];
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
                if (str_contains($split_path[$i], ":")) {
                    $key = explode(':', $split_path[$i]);
                    if (count($split_path) === count($matches)) {
                        $queries[$key[1]] = $matches[$i];
                    } else {
                        $queries[$key[1]] = $matches[$i - 1];
                    }
                }
            }
        }

        $this->route_quries = $queries;
    }

    /**
     * Summary of get_controller_instance
     * 
     * @param \DI\Container $container
     * @return ICoreController
     */
    public function get_controller_instance(Container $container): ICoreController
    {
        $module = $this->phantomHandler->get_module();

        if (empty($module)) {
            PhantomResponse::send404();
            exit;
        }

        $controller_to_execute = $module::CONTROLLER;

        return $container->get($controller_to_execute);
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
