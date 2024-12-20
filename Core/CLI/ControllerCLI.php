<?php

namespace Core\CLI;

use Core\Helpers\FileHandler;

class ControllerCLI
{
    public function __construct(private FileHandler $file_handler) {}
    public function createController($controller_name)
    {
        echo 'CREATING CONTROLLER ' . $controller_name;
        ## create php file with controller name inside Controller folder

        $directory = __DIR__ . '/../../Controller/' . $controller_name;

        $this->file_handler->if_exists_and_create($directory);

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
}
