<?php

namespace Core\CLI;

use Core\Helpers\FileHandler;

class ModuleCLI
{
    public function __construct(private FileHandler $file_handler) {}

    function createModule(string $module_name): void
    {
        echo 'CREATING MODULE ' . $module_name;

        $directory = __DIR__ . '/../../Controller/' . $module_name;

        $this->file_handler->if_exists_and_create($directory);

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
}
