<?php

namespace Controller\Auth;

use Controller\Auth\Dto\LoginDto;
use Core\Database\Database;
use Core\Ui\Forms\FormBuilder;
use Services\FormService\FormService;

class AuthModule
{
    static public $controller = AuthController::class;

    static public function inject()
    {
        return [
            'authService' => new AuthService(new FormService(new FormBuilder()), Database::getInstance()),
        ];
    }

    static public function routes()
    {
        return [
            'routes' => [
                '/login' =>
                [
                    'GET' => [
                        'controller' => 'login_view'
                    ],
                    'POST' => [
                        'controller' => 'login',
                        'dto' => LoginDto::class
                    ]
                ],
                '/signup' =>
                [
                    'GET' => 'signup_view',
                ]
            ]
        ];
    }
}
