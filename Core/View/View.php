<?php

namespace Core\View;

use config\Filesystems;
use Core\Response\PhantomResponse;
use Illuminate\Contracts\Filesystem\Filesystem;

class View
{
    public function get_view($view)
    {
        if (!$view) {
            return false;
        }

        $view_path = VIEW_PATH . $view;

        if (file_exists($view_path)) {
            return $view_path;
        } else {
            PhantomResponse::send404("View not found");
            return false;
        }
    }

    public function get404View()
    {
        $view_path = VIEW_PATH . '404.blade.php';

        if (file_exists($view_path)) {
            return $view_path;
        } else {
            PhantomResponse::send404("View not found");
            return false;
        }
    }
}
