<?php

namespace Controller\Auth;

use Core\Request\Request;

class AuthController
{

    public function __construct(private AuthService $authService)
    {
    }

    public function login_view()
    {
        return $this->authService->login();
    }

    public function login(Request $request)
    {
        $body = $request->getBody();

        return $this->authService->login($body);
    }

    public function signup()
    {
        $new_tenant = $this->authService->create_user([
            'tenant_name' => 'test',
            'tenant_email' => 'email@gmail.com'
        ]);

        return [
            "message" => "User created successfully",
            'new_tenant' => $new_tenant
        ];
    }
}
