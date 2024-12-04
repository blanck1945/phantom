<?php

namespace Services\FormService;

use Core\Ui\Forms\Fields\Input;
use Core\Ui\Forms\Fields\Password;
use Core\Ui\Forms\FormBuilder;

class FormService
{

    public function __construct(private FormBuilder $formBuilder)
    {
    }

    public function login(string $username, string $password)
    {
        return $this->formBuilder->build([
            [
                'type' => Input::class,
                'name' => 'username',
                'label' => 'Nombre del usuario',
                'value' => $username
            ],
            [
                'type' => Password::class,
                'name' => 'password',
                'label' => 'Contraseña del usuario',
                'value' => $password
            ],
        ]);
    }
}
