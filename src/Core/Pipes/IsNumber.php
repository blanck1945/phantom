<?php

namespace Core\Pipes;

use Core\Response\PhantomResponse;

class IsNumber
{
    public function handler(string $value)
    {
        if (!is_numeric($value)) {
            PhantomResponse::send(400, [
                "message" => "The value is not a number",
                "value" => $value
            ]);
        }
    }
}
