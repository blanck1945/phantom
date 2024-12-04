<?php

class PhantomCLI
{
    function create_controller(string $controller_name)
    {
        echo 'CREATING CONTROLLER ' . $controller_name;
        ## create php file with controller name inside Controller folder

        mkdir('Controller/' . $controller_name);

        $controller_file = fopen('Controller/' . $controller_name . '/' . $controller_name . '.php', 'w');


        $controller_content = '<?php
        
namespace Controller\\' . $controller_name . ';

use Core\Controller\ICoreController;

class ' . $controller_name . ' implements ICoreController

{
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

        fwrite($controller_file, $controller_content);

        fclose($controller_file);

        echo 'CONTROLLER ' . $controller_name . ' CREATED';
    }

    function createService(string $service_name)
    {
        echo 'CREATING SERVICE ' . $service_name;
        ## create php file with controller name inside Controller folder

        mkdir('Services/' . $service_name);

        $service_file = fopen('Services/' . $service_name . '/' . $service_name . '.php', 'w');

        $service_content = '<?php
        
namespace Services\\' . $service_name . ';

class ' . $service_name . '
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

        if ($command === 'create:controller') {
            $controller_name = $argv[2];
            $this->create_controller($controller_name);
        }

        if ($command === 'create:service') {
            $service_name = $argv[2];
            $this->createService($service_name);
        }

        echo "No command found - " . $command;
    }
}

$phatom = new PhantomCLI();

$phatom->run_command($argv);
