<?php

declare(strict_types=1);

use Controller\Docs\DocsModule;
use Controller\Home\HomeModule;
use Controller\Menu\MenuModule;
use Controller\Product\ProductModule;
use Controller\User\UserModule;
use Core\Phantom;

function server(): void
{
    try {
        $app = new Phantom();

        $app->register_routes_map(
            HomeModule::class,
            DocsModule::class,
            UserModule::class,
        );

        $app->boostrap();
    } catch (Error $e) {
        echo $e->getMessage();
    }
}
