<?php

namespace Core\CLI;

class Boogie
{
    public function __construct(
        private ModuleCLI $moduleCLI,
        private ServiceCLI $serviceCLI,
        private ControllerCLI $controllerCLI
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
}
