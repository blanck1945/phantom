<?php

namespace App\Http\Controllers\Docs;

use Core\Helpers\Decorators\Controller;
use Core\Helpers\Decorators\Get;
use Core\Interfaces\ICoreController;

#[Controller('docs')]
class DocsController implements ICoreController
{
    #[Get('')]
    public function docs()
    {
        return [
            'view' => 'docs.blade.php',
        ];
    }
}
