<?php

namespace Core\Server;

use Core\Request\PhantomRequest;
use Core\Router\Router;

class Server
{

    public function __construct(
        private array $config,
        private PhantomRequest $request_handler,
        private Router $router_handler
    ) {}

    public function execute_handler($instance, $queries)
    {
        // $hasDto = $this->router_handler->getDto();

        if ($queries) {
            foreach ($queries as $key => $value) {
                $this->request_handler->setParam($key, $value);
            }
        }

        $executable = $instance->{$this->router_handler->get_handler()}($this->request_handler);

        $executable['_token'] = $this->router_handler->get_phantom_handler()->get_csrf();

        return $executable;
    }

    private function get_metadata()
    {
        return $this->config;
    }

    public function set_metadata($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function merge_metadata($page_data)
    {
        $core_metada = $this->get_metadata();

        $page_data['metadata']['css'] = array_merge($core_metada['css'] ?? [], $page_data['metadata']['css'] ?? []);
        $page_data['metadata']['js'] = array_merge($core_metada['js'] ?? [], $page_data['metadata']['js'] ?? []);
        $page_data['metadata']['favicon'] = $core_metada['favicon'];

        return $page_data;
    }

    public function valid_route_guard($route_to_execute)
    {
        if (empty($route_to_execute)) {
            # Check if 404 has a cusotm configurations
            $has_404 = array_key_exists('404', $this->config) ? $this->config['404'] : [];

            if ($has_404) {
                $route_to_execute = $has_404;
            } else {
                require_once __DIR__ . "/../../views/pages/404.php";
            }

            exit(0);
        }
    }

    public function set_json_header($executable)
    {
        if (!array_key_exists('view', $executable)) {
            header('Content-Type: application/json');
        }

        if (array_key_exists('statusCode', $executable)) {
            http_response_code($executable['statusCode']);
        }
    }
}
