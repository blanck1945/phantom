<?php

namespace Controller\Docs;

use Core\Interfaces\ICoreModule;

class DocsModule  implements ICoreModule
{
    static public $controller = DocsController::class;

    static public function config()
    {
        return [];
    }

    static public function inject()
    {
        return [];
    }

    static public function routes()
    {
        return [
            '/docs' =>  [
                'GET' => 'docs'
            ]
        ];
    }
}
