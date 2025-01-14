<?php

namespace Core\Cache;

use Core\Render\PhantomRender;

class PhantomCache
{
    public function read_cache_file(string $path, PhantomRender $render_handler,)
    {
        // Ruta del archivo JSON
        $jsonFile = JSON_CACHE_FILE;

        // Si el archivo existe, leer el contenido
        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $defaultData = json_decode($jsonContent, true) ?? [];

            if (isset($defaultData[$path])) {
                $render_handler->render_from_cache($defaultData[$path]);
                exit;
            }
        }
    }


    public function write_cache_file(string $path, $view)
    {
        // Ruta del archivo JSON
        $jsonFile = JSON_CACHE_FILE;

        // Verificar si el directorio existe, si no, crearlo
        $directory = CACHE_DIR;
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
    }
}
