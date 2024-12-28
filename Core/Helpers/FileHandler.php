<?php

namespace Core\Helpers;

class FileHandler
{
    public function if_exists_and_create($directory)
    {
        // Comprobar si el directorio ya existe
        if (!is_dir($directory)) {
            // Crear el directorio si no existe
            if (mkdir($directory, 0755, true)) {
                echo "Directorio creado exitosamente: $directory";
            } else {
                echo "Error al crear el directorio: $directory";
            }
        }
    }
}
