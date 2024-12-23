<?php

namespace Controller\User;

use Controller\User\Dto\UserDto;
use Core\Interfaces\ICoreModule;

class UserModule  implements ICoreModule
{
    static public $controller = UserController::class;

    static public function config()
    {
        return [];
    }

    static public function inject()
    {
        return ['userService' => UserService::class];
    }

    static public function routes()
    {
        return [
            '/signup' => [
                "GET" => 'signup',
                "POST" => [
                    'controller' => 'register',
                    'dto' => UserDto::class
                ]
            ]
        ];
    }
}
