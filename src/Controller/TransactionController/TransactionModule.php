<?php

namespace Controller\TransactionController;

use Controller\TransactionController\model\TransactionModel;
use Controller\TransactionController\service\TransactionService;
use Core\Interfaces\ICoreModule;

class TransactionModule implements ICoreModule
{
    static public $controller = TransactionController::class;

    static public function config()
    {
        return [
            'metadata' => false,
        ];
    }

    static public function inject()
    {
        return [
            'transaction_service' => new TransactionService(new TransactionModel(), 100, 'ARS', 'steam'),
        ];
    }

    static public function routes()
    {
        return [
            'routes' => [
                '/transaction' => [
                    'GET' => 'get_transaction',
                ],
                '/transaction/heal' => [
                    'GET' => 'heal'
                ],
                '/transaction/create' => [
                    'POST' => 'create'
                ],
            ]
        ];
    }
}
