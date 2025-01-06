<?php

namespace Core\CLI;

use Core\Database\DbManager;
use Core\Helpers\FileHandler;
use DI\Container;

class MigrationsCLI
{
    public function __construct(
        private Container $container,
        private DbManager $dbManager,
        private FileHandler $file_handler
    ) {}

    public function init_migrations()
    {
        echo 'INITIALIZING MIGRATIONS' . PHP_EOL;

        $this->dbManager->createTable('migrations', [
            $this->dbManager->primaryKey('id'),
            $this->dbManager->string('migration_name'),
            $this->dbManager->timestamp('created_at')
        ]);

        echo 'MIGRATIONS INITIALIZED';
    }

    public function run_single_migration(string $migration_name)
    {
        echo 'RUNNING MIGRATION ' . $migration_name;

        // require file and use the up method
        $directory = __DIR__ . '\\..\\..\\database\\migrations\\' . $migration_name . '.php';

        $classesBefore = get_declared_classes();

        // Incluir el archivo de migración
        require_once $directory;

        // Obtener todas las clases declaradas después de incluir el archivo
        $classesAfter = get_declared_classes();

        // Identificar la nueva clase definida
        $newClasses = array_diff($classesAfter, $classesBefore);

        // Si hay una nueva clase, instánciala dinámicamente
        if (!empty($newClasses)) {
            $migrationClass = reset($newClasses); // Obtiene la primera nueva clase
            $migration = $this->container->get($migrationClass);
            $migration->up(); // Asumiendo que tiene un método 'up'

            $this->dbManager->insert('migrations', [
                'migration_name' => $migration_name,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            echo 'MIGRATION ' . $migration_name . ' RAN';
        } else {
            echo "No se encontró una clase nueva en el archivo de migración.";
        }
    }

    public function drop_single_migration(string $migration_name)
    {
        echo 'DROPPING MIGRATION ' . $migration_name;

        // require file and use the up method
        $directory = __DIR__ . '\\..\\..\\database\\migrations\\' . $migration_name . '.php';

        $classesBefore = get_declared_classes();

        // Incluir el archivo de migración
        require_once $directory;

        // Obtener todas las clases declaradas después de incluir el archivo
        $classesAfter = get_declared_classes();

        // Identificar la nueva clase definida
        $newClasses = array_diff($classesAfter, $classesBefore);

        // Si hay una nueva clase, instánciala dinámicamente
        if (!empty($newClasses)) {
            $migrationClass = reset($newClasses); // Obtiene la primera nueva clase
            $migration = $this->container->get($migrationClass);
            $migration->down(); // Asumiendo que tiene un método 'down'

            echo 'MIGRATION ' . $migration_name . ' DROPPED';
        } else {
            echo "No se encontró una clase nueva en el archivo de migración.";
        }
    }
}
