<?php

namespace Core\Helpers;

class Param
{
    public static function __callStatic(string $name, array $pipes)
    {

        $props = $_POST[$name] ?? false;

        if (!$props) {
            return;
        }


        if (count($pipes) === 0) {
            return $props;
        }


        echo "Pipes: " . json_encode($pipes) . PHP_EOL;
    }
}
