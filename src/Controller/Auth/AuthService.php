<?php

namespace Controller\Auth;

use Core\Database\Database;
use Services\FormService\FormService;

class AuthService
{
    public function __construct(private FormService $formService, private Database $db)
    {
    }

    public function login($body = [])
    {
        $login_form = $this->formService->login($body['username'] ?? '', $body['password'] ?? '');

        return [
            'view' => 'login.php',
            'form' =>  $login_form
        ];
    }

    public function create_user(array $user)
    {

        ##$this->db->insert('users', $user);

        return 'new user';
    }
}
