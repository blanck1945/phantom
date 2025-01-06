<?php

namespace Core\CLI;

use Core\Helpers\FileHandler;

class ModelCLI
{
    public function __construct(private FileHandler $file_handler) {}
    public function createModel($modelName)
    {
        echo 'CREATING MODEL ' . $modelName;
        ## create php file with model name inside Model folder

        $directory = __DIR__ . '\\..\\..\\app\\Http\\Controller\\' . $modelName . '\\model';

        $this->file_handler->if_exists_and_create($directory);

        $model_file = fopen($directory . '\\' . $modelName . "Model.php", 'w');

        $model_content = '<?php

namespace App\Http\Controller\\' . $modelName . '\\model;

use Illuminate\Database\Eloquent\Model;

class ' . $modelName . 'Model extends Model
    {}
        ';

        fwrite($model_file, $model_content);

        fclose($model_file);

        echo 'MODEL ' . $modelName . ' CREATED';
    }
}
