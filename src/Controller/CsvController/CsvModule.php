<?php

namespace Controller\CsvController;

use Controller\CsvController\CsvController;
use Core\Database\Database;
use Core\Interfaces\ICoreModule;

class CsvModule implements ICoreModule
{
    public static $controller = CsvController::class;

    public static function inject()
    {
        return [
            'csv_service' => new CsvService(Database::getInstance()),
        ];
    }

    public static function routes()
    {
        return [
            'routes' => [
                '/read' =>
                [
                    'GET' => 'read_csv'
                ],
                '/read/:csv' =>
                [
                    'GET' => 'read_csv'
                ]
            ]
        ];
    }
}
