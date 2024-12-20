<?php


require_once 'vendor/autoload.php';

use Core\CLI\Boogie;

class PhantomCLI
{
    public function all($service_name): void
    {
        $this->create_module($service_name);
        $this->create_controller($service_name);
        $this->createService($service_name);
    }


    public function create_module(string $module_name)
    {
        echo 'CREATING MODULE ' . $module_name;
        ## create php file with controller name inside Controller folder

        mkdir('Controller/' . $module_name);

        $module_file = fopen('Controller/' . $module_name . '/' . $module_name  . 'Module.php', 'w');

        $module_content = '<?php

namespace Controller\\' . $module_name . ';

use Core\Interfaces\ICoreModule;

class ' . $module_name . 'Module ' . ' implements ICoreModule
{
    static public $controller = ' . $module_name . 'Controller::class;

    static public function config()
    {
        return [];
    }

    static public function inject()
    {
        return [];
    }

    static public function routes()
    {
        return [];
    }

}
        ';

        fwrite($module_file, $module_content);

        fclose($module_file);

        echo 'MODULE ' . $module_name . ' CREATED';
    }

    function create_controller(string $controller_name)
    {
        echo 'CREATING CONTROLLER ' . $controller_name;
        ## create php file with controller name inside Controller folder

        mkdir('Controller/' . $controller_name);

        $controller_file = fopen('Controller/' . $controller_name . '/' . $controller_name . 'Controller.php', 'w');


        $controller_content = '<?php
        
namespace Controller\\' . $controller_name . ';

class ' . $controller_name . 'Controller
{
}
        ';

        fwrite($controller_file, $controller_content);

        fclose($controller_file);

        echo 'CONTROLLER ' . $controller_name . ' CREATED';
    }

    function createService(string $service_name)
    {
        echo 'CREATING SERVICE ' . $service_name;
        ## create php file with controller name inside Controller folder

        mkdir('Services/' . $service_name);

        $service_file = fopen('Controller/' . $service_name . '/' . $service_name . 'Service.php', 'w');

        $service_content = '<?php
        
namespace Controller\\' . $service_name . ';

class ' . $service_name . 'Service
{
}
        ';

        fwrite($service_file, $service_content);

        fclose($service_file);

        echo 'SERVICE ' . $service_name . ' CREATED';
    }


    function run_command($argv)
    {
        $command = $argv[1];

        $container = new DI\Container();
        $boogie = $container->get(Boogie::class);

        match ($command) {
            'create:all' => $boogie->create_all($argv[2]),
            'create:module' => $boogie->create_module($argv[2]),
            'create:controller' => $boogie->create_controller($argv[2]),
            'create:service' => $boogie->create_service($argv[2]),
            default => var_dump("No command found - " . $command),
        };
    }
}

$phatom = new PhantomCLI();

$phatom->run_command($argv);
