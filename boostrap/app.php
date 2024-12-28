<?php

declare(strict_types=1);

namespace Boostrap;

use App\Http\Controller\Docs\DocsModule;
use App\Http\Controller\Home\HomeModule;
use App\Http\Controller\User\UserModule;
use Core\Phantom;

$phantom = new Phantom();

$phantom->loadEnv();

$phantom->register_routes_map(
    HomeModule::class,
    DocsModule::class,
    UserModule::class
);

$phantom->boostrap();
