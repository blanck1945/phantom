<?php

namespace Core\Helpers\Enums;

enum RenderMethod: string
{
    case STATIC = 'static';
    case DYNAMIC = 'dynamic';
    case REVALIDATE = 'revalidate';
    case FORCE = 'force';
}
