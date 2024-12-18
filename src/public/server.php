<?php

declare(strict_types=1);

use Controller\Docs\DocsModule;
use Core\Phantom;
use Controller\ViewController\ViewModule;

function server(): void
{
    try {
        $app = new Phantom();

        $app->register_routes_map(
            ViewModule::class,
            DocsModule::class
        );

        $app->boostrap();
    } catch (Error $e) {
        echo $e->getMessage();
    }
}
