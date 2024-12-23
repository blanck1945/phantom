<?php

namespace Controller\User;

use src\Enums\MiseCategories;

class UserService
{

    public function signup()
    {
        return [
            'view' => 'signup.blade.php',
            'categories' => MiseCategories::cases()
        ];
    }
}
