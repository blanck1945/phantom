<?php

namespace Core\Exception;

class ViewException
{

    public function notFound()
    {
        return [
            'view' => '404.blade.php'
        ];
    }

    public function InternalServerError($view)
    {
        echo "Internal Server Error: $view";
    }
}
