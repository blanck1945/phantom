<?php

namespace Core\Response;

class Response
{
    public static function set_http_response_code(int $code)
    {
        http_response_code($code);
    }
}
