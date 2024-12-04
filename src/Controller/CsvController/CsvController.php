<?php

namespace Controller\CsvController;

use Core\Database\Database;


class CsvController
{
    public function __construct(public CsvService $csv_service)
    {
    }

    static public function config()
    {
        return [
            'metadata' => false,
        ];
    }

    public function csv_info($request)
    {
        return [
            'name' => 'csv_info',
            'description' => 'Get info about the csv',
            'params' => [
                'csv' => [
                    'type' => 'string',
                    'required' => true,
                    'description' => 'The csv name'
                ]
            ]
        ];
    }

    public function read_csv($request)
    {
        $csv = $request->getParam('csv');

        return $this->csv_service->read_csv($csv);
    }
}
