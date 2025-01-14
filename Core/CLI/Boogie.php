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

    /**
     * Run all migrations - default dir is database/migrations
     * 
     * @return void
     */
    public function run_all()
    {
        $this->migrationsCLI->run_all_migrations();
    }

    public function run_single_migration($arg)
    {
        $this->migrationsCLI->run_single_migration($arg);
    }

    /**
     * Drop all migrations - default dir is database/migrations 
     * 
     * @return void
     */
    public function drop_all()
    {
        $this->migrationsCLI->drop_all_migrations();
    }

    public function drop_migration($arg)
    {
        $this->migrationsCLI->drop_single_migration($arg);
    }
}
