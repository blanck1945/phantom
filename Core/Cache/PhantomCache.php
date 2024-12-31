<?php

namespace Core\Cache;

use config\Filesystems;
use Core\Render\Render;

class PhantomCache
{
    public function read_cache_file(string $path, Render $render_handler)
    {
        // Ruta del archivo JSON
        $jsonFile = Filesystems::JSON_CACHE_FILE;

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
        $jsonFile = Filesystems::JSON_CACHE_FILE;

        // Verificar si el directorio existe, si no, crearlo
        $directory = Filesystems::CACHE_DIR;
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

        // crear el archivo JSON
        // $cache = __DIR__ . '/Cache/views/';
        // $cacheFile = $cache . $path . '.blade.php';

        // if (!file_exists($cacheFile)) {
        //     file_put_contents($cacheFile, $view);
        // }
    }
}
