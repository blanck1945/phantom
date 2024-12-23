<?php

namespace Controller\User;

use Core\Request\PhantomRequest;

class UserController
{
    public function __construct(private UserService $userService) {}

    public function signup()
    {
        return $this->userService->signup();
    }

    public function register(PhantomRequest $request)
    {
        $body = $request->getBody();

        var_dump($body);

        return [
            'view' => 'success.blade.php',
            'mise_name' => $body['mise_name']
        ];
    }
}
