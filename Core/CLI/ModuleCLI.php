<?php

namespace Core\CLI;

use config\Filesystems;
use Core\Helpers\FileHandler;

class ModuleCLI
{
    public function __construct(private FileHandler $file_handler) {}

    function createModule(string $module_name): void
    {
        echo 'CREATING MODULE ' . $module_name;

        $directory = Filesystems::getPath(Filesystems::$controllersPath) . $module_name;

        $this->file_handler->if_exists_and_create($directory);

        $module_file = fopen($directory . '\\' . $module_name  . 'Module.php', 'w');

        $module_content = '<?php

namespace App\Http\Controller\\' . $module_name . ';

use Core\Interfaces\ICoreModule;

class ' . $module_name . 'Module ' . 'implements ICoreModule
{
    public CONST CONTROLLER = ' . $module_name . 'Controller::class;

    public static function routes()
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
