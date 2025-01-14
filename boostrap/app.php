<?php

declare(strict_types=1);

namespace Boostrap;

use App\Http\Controllers\Home\HomeModule;
use App\Http\Controllers\Docs\DocsModule;
use Core\Helpers\Middlewares\CsrfMiddleware;
use Core\Phantom;

$phantom = new Phantom();

$phantom->setDb();

/*
** Uncomment this line if you want to use the JWT strategy
*/
// $phantom->setAuthStrategy();

$phantom->register_routes_map(
    HomeModule::class,
    DocsModule::class,
);

$phantom->set_middlewares([
    CsrfMiddleware::class
]);

$phantom->boostrap();
