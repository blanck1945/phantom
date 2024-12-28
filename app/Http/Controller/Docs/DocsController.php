<?php

namespace App\Http\Controller\Docs;

class DocsController
{

    public function docs()
    {
        return [
            'view' => 'docs.blade.php',
        ];
    }
}
