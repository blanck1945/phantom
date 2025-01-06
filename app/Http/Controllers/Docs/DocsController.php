<?php

namespace App\Http\Controllers\Docs;

class DocsController
{

    public function docs()
    {
        return [
            'view' => 'docs.blade.php',
        ];
    }
}
