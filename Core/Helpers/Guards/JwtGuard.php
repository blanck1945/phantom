<?php

namespace Core\Helpers\Guards;

use config\AuthConfig;
use Core\Helpers\Enums\AuthStrategies;
use Core\Interfaces\cookie\CookieServiceInterface;
use Core\Response\PhantomResponse;

class JwtGuard
{
    public function __construct(
        private readonly CookieServiceInterface $cookieService,
        private readonly JwtUser $jwtUser
    ) {}

    public function handler()
    {
        if (AuthConfig::getAuthConfig() === AuthStrategies::SESSION) {
            return true;
        }

        if ($this->cookieService->get(JWT_AUTH_NAME)) {
            $this->jwtUser->handler();
            return true;
        }

        PhantomResponse::redirect('/login', 302);
    }
}
