<?php

declare(strict_types=1);

use Core\Phantom;
use Controller\Auth\AuthModule;
use Controller\CsvController\CsvModule;
use Controller\TransactionController\TransactionModule;
use Controller\ViewController\ViewModule;
use Core\Env\Env;

function server()
{
    $app = new Phantom();

    // $app->setDb(
    //     connectionString: Env::get('DB_CONNECTION_STRING'),
    //     user: Env::get('DB_USER'),
    //     password: Env::get('DB_PASSWORD'),
    // );

    $app->set_cors();

    $app->register_routes(
        ViewModule::class,
        TransactionModule::class,
        CsvModule::class,
        AuthModule::class
    );

    $app->set_configuration();

    $app->set_metadata(
        css: [
            'styles.css'
        ],
        favicon: 'https://i.bandori.party/u/asset/xBsea5Garupa-PICO-Logo-vKPCXC.png',
    );

    // $app->set_middlewares();

    // $app->set_guards();

    // $app->set_interceptor();

    $app->boostrap();
}
