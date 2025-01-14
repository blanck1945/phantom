<?php

namespace Core\CLI;

use Core\Helpers\FileHandler;

class ServiceCLI
{
    public function __construct(private FileHandler $file_handler) {}

    function createService(string $service_name)
    {
        echo 'CREATING SERVICE ' . $service_name;

        $directory = CONTROLLERS_PATH  . $service_name;

        $this->file_handler->if_exists_and_create($directory);

        $service_file = fopen($directory . '\\' . $service_name . 'Service.php', 'w');

        $service_content = '<?php

namespace App\Http\Controllers\\' . $service_name . ';

class ' . $service_name . 'Service
{
}
';

        fwrite($service_file, $service_content);

        fclose($service_file);

        // open Module file and inject the new module

        // $directoy_files = scandir($directory);

        // foreach ($directoy_files as $file) {
        //     if ($file === $service_name . 'Module.php') {
        //         $file_path = $directory . '\\' . $service_name . 'Module.php';
        //         $module_file = fopen($file_path, 'r');

        //         echo "Abriendo el archivo " . $service_name . "Module.php\n";
        //         if ($module_file) {
        //             // Leer el contenido completo del archivo
        //             $file_content = fread($module_file, filesize('Controller/' . $service_name . '/' . $service_name . 'Module.php'));

        //             $pattern = '/(static\s+public\s+function\s+inject\s*\(\s*\)\s*{)(.*?)(})/s';

        //             if (preg_match($pattern, $file_content, $matches)) {
        //                 $function_body = $matches[2]; // Contenido actual de la función
        //             } else {
        //                 die("No se encontró la función inject.");
        //             }

        //             // trim the function body
        //             $function_body = trim($function_body);

        //             // Cantidad de lineas en el contenido de la función
        //             $lines = substr_count($function_body, "\n");

        //             // Remove last two characters from the function body
        //             $function_body = substr($function_body, 0, -2);

        //             // downcase the first letter of the service name
        //             $service_name_key = lcfirst($service_name) . 'Service';

        //             $service_class = $service_name . 'Service::class';

        //             if ($lines > 0) {
        //                 $new_content = ",'$service_name_key' => {$service_class}];";
        //             } else {
        //                 $new_content = "'$service_name_key' => {$service_class}];";
        //             }

        //             $updated_function_body = $function_body . $new_content;

        //             // Reemplazar el contenido de la función en el archivo completo
        //             $file_content = preg_replace(
        //                 $pattern,
        //                 '${1}' . $updated_function_body . '${3}',
        //                 $file_content
        //             );

        //             if (file_put_contents($file_path, $file_content)) {
        //                 echo "El archivo se actualizó correctamente.";
        //             } else {
        //                 echo "Hubo un error al intentar guardar los cambios.";
        //             }

        //             // Cerrar el archivo después de leer
        //             fclose($module_file);
        //         }
        //     }
        // }

        echo 'SERVICE ' . $service_name . ' CREATED';
    }
}
