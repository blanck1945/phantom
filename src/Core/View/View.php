<?php

namespace Core\View;

use Core\Response\PhantomResponse;

class View
{

    private string $view_path = '/pages/';

    public function __construct(private string $base_url) {}
    public function get_view_path()
    {
        return $this->view_path;
    }

    public function set_view_path(string $path)
    {
        $this->view_path = $path;
    }

    public function get_view($view)
    {
        if (!$view) {
            return false;
        }

        $view_path =     $this->view_path . $view;
        $path = $this->base_url . $view_path;

        if (file_exists($path)) {
            return $path;
        } else {
            PhantomResponse::send404("View not found");
            return false;
        }
    }
}
