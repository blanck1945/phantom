<?php

namespace config;

use Core\Helpers\Enums\AuthStrategies;

class AuthConfig
{
    public function setAuthConfig() {}

    public static function getAuthConfig(): AuthStrategies
    {
        return AuthStrategies::JWT;
    }
}
