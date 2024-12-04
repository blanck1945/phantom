<?php

namespace Core\Render;

use Core\View\View;
use eftec\bladeone\BladeOne;

class Render
{

    public function __construct(private View $view_handler) {}

    public function render($page_data, $route_config, $view)
    {
        ## Server view handler. ##
        if ($view) {

            $filenameWithExtension = basename($view); // home.blade.php
            $filenameWithoutTemplateExtension = str_replace('.blade.php', '', $filenameWithExtension); // Elimina '.blade.php'
            $filename = pathinfo($filenameWithoutTemplateExtension, PATHINFO_FILENAME);

            $views = __DIR__ . '/../../views/pages/';
            $cache = __DIR__ . '/../../cache/';
            // Inicializar BladeOne
            $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

            // Renderizar una vista
            echo $blade->run($filename, ['page_data' => $page_data]);
            // require_once $view;
        }
        ## Server JSON handler. ##
        else {
            if (isset($route_config['metadata']) && $route_config['metadata'] === false) {
                unset($page_data['metadata']);
            }

            if (empty($page_data)) {
                return;
            }

            ob_start();
            echo json_encode($page_data);
            ob_end_flush();
        }
    }
}
