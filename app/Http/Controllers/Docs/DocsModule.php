<?php

namespace App\Http\Controllers\Docs;

use Core\Helpers\Decorators\Module;
use Core\Interfaces\ICoreModule;

#[Module(DocsController::class)]
class DocsModule  implements ICoreModule {}
