<?php

namespace Core\Services\FormService;

use Core\Ui\Forms\Fields\Input;
use Core\Ui\Forms\Fields\Password;
use Core\Ui\Forms\FormBuilder;

class FormService
{

    public function __construct(public readonly FormBuilder $formBuilder) {}

    public function signup(
        string $name = '',
        string $email = '',
        string $password = '',
        string $confirmPassword = '',
        array $errors = []
    ) {
        return $this->formBuilder->build([
            [
                'type' => Input::class,
                'name' => 'name',
                'placeholder' => 'Nombre',
                'value' => $name,
                'error' => $errors['name'] ?? ''
            ],
            [
                'type' => Input::class,
                'name' => 'email',
                'placeholder' => 'Email',
                'value' => $email,
                'error' => $errors['email'] ?? ''
            ],
            [
                'type' => Password::class,
                'name' => 'password',
                'placeholder' => 'Contraseña',
                'value' => $password,
                'error' => $errors['password'] ?? ''
            ],
            [
                'type' => Password::class,
                'name' => 'confirmPassword',
                'placeholder' => 'Confirmar contraseña',
                'value' => $confirmPassword,
                'error' => $errors['confirmPassword'] ?? ''
            ],
        ]);
    }

    public function login(string $username = '', string $password = '', array $errors = [])
    {
        return $this->formBuilder->build([
            [
                'type' => Input::class,
                'name' => 'email',
                'placeholder' => 'Email',
                'value' => $username,
                'error' => $errors['email'] ?? ''
            ],
            [
                'type' => Password::class,
                'name' => 'password',
                'placeholder' => 'Contraseña',
                'value' => $password,
                'error' => $errors['password'] ?? ''
            ],
        ]);
    }
}
