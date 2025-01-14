<?php

namespace Core\Services\JwtService;

use Core\Env\Env;
use Core\Interfaces\cookie\CookieServiceInterface;

class JwtService
{
    public function __construct(private readonly CookieServiceInterface $cookieService) {}

    public function base64UrlEncode(string $data): string
    {
        // Codifica la cadena en Base64
        $base64 = base64_encode($data);

        // Sustituye los caracteres que no son seguros en URLs
        $urlSafe = strtr($base64, '+/', '-_');

        // Elimina el relleno `=` del final
        return rtrim($urlSafe, '=');
    }

    public function base64UrlDecode(string $data): string
    {
        // Reemplaza los caracteres URL-safe por los originales
        $base64 = strtr($data, '-_', '+/');

        // Decodifica el Base64
        return base64_decode($base64);
    }

    public function createJwt(array $payload): string
    {
        // Header
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $base64Header = $this->base64UrlEncode($header);

        // Payload
        $base64Payload = $this->base64UrlEncode(json_encode($payload));

        $secret = Env::get('JWT_SECRET');

        // Signature
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
        $base64Signature = $this->base64UrlEncode($signature);

        // Token
        return "$base64Header.$base64Payload.$base64Signature";
    }

    public function setToken(array $value, string $tokenName = 'auth_token')
    {
        $jwt = $this->createJwt($value);

        $this->cookieService->set($tokenName, $jwt);
    }
}
