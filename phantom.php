<?php


require_once 'vendor/autoload.php';

use Core\CLI\Boogie;

class PhantomCLI
{
    function run_command($argv)
    {
        $command = $argv[1];

        $container = new DI\Container();
        $boogie = $container->get(Boogie::class);

        match ($command) {
            'create:all' => $boogie->create_all($argv[2]),
            'create:module' => $boogie->create_module($argv[2]),
            'create:controller' => $boogie->create_controller($argv[2]),
            'create:service' => $boogie->create_service($argv[2]),
            default => var_dump("No command found - " . $command),
        };
    }
}

$phatom = new PhantomCLI();

$phatom->run_command($argv);
