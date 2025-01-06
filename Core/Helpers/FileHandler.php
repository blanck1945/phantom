<?php

namespace Core\Helpers;

class FileHandler
{
    public function if_exists_and_create($directory)
    {
        $directoryExists = is_dir($directory);
        // Comprobar si el directorio ya existe
        if ($directoryExists === false) {
            // Crear el directorio si no existe
            if (mkdir($directory, 0755, true)) {
                echo "Directorio creado exitosamente: $directory";
            } else {
                echo "Error al crear el directorio: $directory";
            }
        }
    }
}
