<?php

namespace Core\Helpers\Guards;

use config\AuthConfig;
use Core\Helpers\Enums\AuthStrategies;
use Core\Response\PhantomResponse;

class SessionGuard
{
    public function handler()
    {
        if (AuthConfig::getAuthConfig() === AuthStrategies::JWT) {
            return true;
        }

        if (isset($_SESSION['user'])) {
            return true;
        }

        PhantomResponse::redirect('/login', 302);
    }
}
