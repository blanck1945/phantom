<?php

namespace Core\Interfaces;

interface IValidator
{
    public function error_case(array $body, array $errors): array;
}
