<?php

namespace Core\CLI;

use config\Filesystems;
use Core\Helpers\FileHandler;

class ControllerCLI
{
    public function __construct(private FileHandler $file_handler) {}
    public function createController($controller_name)
    {
        echo 'CREATING CONTROLLER ' . $controller_name;
        ## create php file with controller name inside Controller folder

        $directory = Filesystems::getPath(Filesystems::$controllersPath) . $controller_name;
        $this->file_handler->if_exists_and_create($directory);

        $controller_file = fopen($directory . '\\' . $controller_name . "Controller.php", 'w');

        $controller_content = '<?php
        
namespace App\Http\Controller\\' . $controller_name . ';

use Core\Interfaces\ICoreController;

class ' . $controller_name . 'Controller implementation ICoreController
{
}
        ';

        fwrite($controller_file, $controller_content);

        fclose($controller_file);

        echo 'CONTROLLER ' . $controller_name . ' CREATED';
    }
}
