<?php

namespace Core\Helpers\Guards;

use App\Http\Controllers\User\Dto\LoggedDto;
use config\AuthConfig;
use Core\Helpers\Enums\AuthStrategies;
use Core\Interfaces\cookie\CookieServiceInterface;
use Core\Services\JwtService\JwtService;

class JwtUser
{
    public function __construct(
        private readonly CookieServiceInterface $cookieService,
        private readonly JwtService $jwtService
    ) {}

    public function handler()
    {
        try {
            $authToken = $this->cookieService->get(JWT_AUTH_NAME);

            $parts = explode('.', $authToken);

            if (count($parts) !== 3) {
                die('JWT inválido');
            }

            $payload =  $this->jwtService->base64UrlDecode(str_replace(['-', '_'], ['+', '/'], $parts[1]));

            $data = json_decode($payload, true);

            $_REQUEST['user'] = new LoggedDto($data);
        } catch (\Exception $e) {
            die('JWT inválido');
        }
    }
}
