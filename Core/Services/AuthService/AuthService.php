<?php

namespace Core\Services\AuthService;

use Core\Env\Env;
use Core\Helpers\Enums\AuthStrategies;
use Core\Services\CookieService\CookieService;

class AuthService
{
    public function __construct(private CookieService $cookieService) {}

    public static function setAuthStrategy(AuthStrategies $authStrategies)
    {
        if ($authStrategies == AuthStrategies::JWT) {
        }

        if ($authStrategies == AuthStrategies::SESSION) {
        }
    }
}
