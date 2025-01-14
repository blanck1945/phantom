<?php

namespace Core\Helpers\Enums;

enum AuthStrategies: string
{
    case JWT = 'jwt';
    case SESSION = 'session';
}
