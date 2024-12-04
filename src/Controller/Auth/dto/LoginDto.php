<?php

namespace Controller\Auth\Dto;

class LoginDto
{
    private string $username;
    private string $password;
    public function __construct(private array $body)
    {
        $this->username = $body['username'] ?? '';
        $this->password = $body['password'] ?? '';
    }
}
