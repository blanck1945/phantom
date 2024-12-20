<?php

namespace Core\Helpers\Pipes;

use Core\Response\PhantomResponse;

class IsPositive
{
    public function handler(int $value)
    {
        echo "IsPositive: " . $value . PHP_EOL;
        if ($value <= 0) {
            PhantomResponse::send(400, [
                "message" => "The value is not a positive number",
                "value" => $value
            ]);
        }
    }
}
