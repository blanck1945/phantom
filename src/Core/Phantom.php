<?php

namespace Core;

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
    private $container;

    private $databaseReferenceName = 'database';

    public function __construct(private array $config = [])
    {
        self::$ROOT_DIR = dirname(__DIR__);
        $this->request = new PhantomRequest();
        $this->database = null;
        $this->router_handler = new Router($this->request, $this->database);
        $this->cors_handler = new Cors();
        $this->server_handler = new Server($config['metadata'] ?? [], $this->request, $this->router_handler);
        $this->view_handler = new View(self::$ROOT_DIR . "/views");
        $this->render_handler = new Render($this->view_handler);
        $this->metadata_handler = new Metadata($config['metadata'] ?? []);
        $this->configuration = $config['configuration'] ?? [];
        $this->container = new Container([
            'Core\Database\Database' => function () {
                return Database::getInstance();
            },
        ]);
    }

    public function boostrap()
    {
        $this->read_cache_file($this->request->get_path());

        $this->check_if_we_should_execute_route();

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
        $DEV_MODE = Env::get('DEV_MODE');

        if ($DEV_MODE === 'false') {
            $this->write_cache_file($this->request->get_path(), $executable['view'] ?? null);
        }

        $view = $this->view_handler->get_view($executable['view'] ?? null);

        $this->render_handler->render($executable, $route_config, $view);
    }

    public function check_if_we_should_execute_route()
    {
        // read Cache/views 
        $cacheViews = __DIR__ . '/Cache/views/';
        $cacheViewsFiles = scandir($cacheViews);

        $requestPath = $this->request->get_path();

        foreach ($cacheViewsFiles as $file) {
            if ($requestPath === '/' && $file === 'index.blade.php') {
                $cacheFile = $cacheViews . $file;
                $cacheFileContent = file_get_contents($cacheFile);

                echo $cacheFileContent;
                exit;
            }

            // remove first / from request path
            $parsedRequestPath = ltrim($requestPath, '/');

            if ($file === $parsedRequestPath . '.blade.php') {
                $cacheFile = $cacheViews . $file;
                $cacheFileContent = file_get_contents($cacheFile);

                echo $cacheFileContent;
                exit;
            }
        }

        if (empty($this->router_handler->get_module_to_execute())) {
            $route_config = $this->router_handler->get_controller_config();

            $view = $this->view_handler->get_view('404.blade.php');

            $this->render_handler->render([], $route_config, $view);
            exit;
        }
    }

    public function read_cache_file($path)
    {
        // Ruta del archivo JSON
        $jsonFile = __DIR__ . '/Cache/views/directory.json';

        // Si el archivo existe, leer el contenido
        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $defaultData = json_decode($jsonContent, true) ?? [];

            if (isset($defaultData[$path])) {
                $viewDir = __DIR__ . '/../views/pages/';
                $view = $viewDir . $defaultData[$path];

                $this->render_handler->render_from_cache($view);
                echo "render from cache";
                exit;
            }
        }
    }

    public function write_cache_file(string $path, $view)
    {
        echo "write_cache_file";
        // Ruta del archivo JSON
        $jsonFile = __DIR__ . '/Cache/views/directory.json';

        // Verificar si el directorio existe, si no, crearlo
        $directory = __DIR__ . '/Cache/views';
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                die("Error: No se pudo crear el directorio.");
            }
        }

        // Si el archivo existe, leer el contenido
        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $defaultData = json_decode($jsonContent, true) ?? [];
        }
        // Agregar el nuevo dato
        $defaultData[$path] = $view;

        // Guardar el JSON actualizado
        $jsonView = json_encode($defaultData, JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $jsonView);

        // crear el archivo JSON
        // $cache = __DIR__ . '/Cache/views/';
        // $cacheFile = $cache . $path . '.blade.php';

        // if (!file_exists($cacheFile)) {
        //     file_put_contents($cacheFile, $view);
        // }
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
        $instance = Database::getInstance();

        $instance->connect(
            $user,
            $password,
            $connectionString,
        );


        $this->databaseReferenceName = $databaseReference;
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
