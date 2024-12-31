<?php

declare(strict_types=1);

namespace Boostrap;

use App\Http\Controller\Docs\DocsModule;
use App\Http\Controller\Home\HomeModule;
use Core\Phantom;

$phantom = new Phantom();

$phantom->setDb();

$phantom->register_routes_map(
    HomeModule::class,
    DocsModule::class,
);

$phantom->boostrap();
