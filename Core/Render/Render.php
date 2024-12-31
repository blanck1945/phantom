<?php

namespace Core\Render;

use Core\Env\Env;
use Core\View\View;
use eftec\bladeone\BladeOne;

class Render
{

    public function __construct(private View $view_handler) {}

    public function render_from_cache($view)
    {
        $coreCache = __DIR__ . '/../Cache/views/';

        $completePath = $coreCache . $view;

        $cacheFileContent = file_get_contents($completePath);

        echo $cacheFileContent;
    }

    public function render($page_data, $route_config, $view)
    {
        ## Server view handler. ##
        if ($view) {

            $filenameWithExtension = basename($view); // home.blade.php
            $filenameWithoutTemplateExtension = str_replace('.blade.php', '', $filenameWithExtension); // Elimina '.blade.php'
            $filename = pathinfo($filenameWithoutTemplateExtension, PATHINFO_FILENAME);

            $views = __DIR__ . '/../../views/pages/';
            $cache = __DIR__ . '/../Cache/blade/';
            $coreCache = __DIR__ . '/../Cache/views/';

            if (!is_dir($cache)) {
                mkdir($cache, 0755, true);
            }

            if (!is_dir($coreCache)) {
                mkdir($coreCache, 0755, true);
            }

            // Inicializar BladeOne
            $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

            // Renderizar una vista
            $bladeView = $blade->run($filename, ['page_data' => $page_data]);

            // write blade view to cache

            if (Env::get('DEV_MODE') === 'false') {
                file_put_contents($coreCache . $filename . '.blade.php', $bladeView);
            }

            echo $bladeView;
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
