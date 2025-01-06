<?php

namespace Core\CLI;

class Boogie
{
    public function __construct(
        private ModuleCLI $moduleCLI,
        private ServiceCLI $serviceCLI,
        private ControllerCLI $controllerCLI,
        private ModelCLI $modelCLI,
        private MigrationsCLI $migrationsCLI
    ) {}

    public function create_all($arg)
    {
        $this->moduleCLI->createModule($arg);
        $this->controllerCLI->createController($arg);
        $this->serviceCLI->createService($arg);
    }

    public function create_service($arg)
    {
        $this->serviceCLI->createService($arg);
    }

    public function create_module($arg)
    {
        $this->moduleCLI->createModule($arg);
    }

    public function create_controller($arg)
    {
        $this->controllerCLI->createController($arg);
    }

    public function create_model($arg)
    {
        $this->modelCLI->createModel($arg);
    }

    /*
    |--------------------------------------------------------------------------
    | Migrations commands
    |--------------------------------------------------------------------------
    |
    */

    public function init_migrations()
    {
        $this->migrationsCLI->init_migrations();
    }

    public function run_single_migration($arg)
    {
        $this->migrationsCLI->run_single_migration($arg);
    }

    public function drop_migration($arg)
    {
        $this->migrationsCLI->drop_single_migration($arg);
    }
}
