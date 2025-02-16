<?php

namespace Core\Render;

use config\GlobalVariables;
use Core\Cache\PhantomCache;
use Core\Env\Env;
use Core\Metadata\Metadata;
use Core\Request\PhantomRequest;
use Core\Router\Router;
use Core\View\View;
use eftec\bladeone\BladeOne;

class PhantomRender
{

    public function __construct(
        private Metadata $metadata_handler,
        private PhantomCache $cache_handler,
        private PhantomRequest $request,
        private View $view_handler,
        private GlobalVariables $globalVariables
    ) {}

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

            $views = dirname(__DIR__) . '/../views/pages/';
            $cache = __DIR__ . '/../Cache/blade/';
            $coreCache = __DIR__ . '/../Cache/views/';

            if (!is_dir($cache)) {
                mkdir($cache, 0755, true);
            }

            if (!is_dir($coreCache)) {
                mkdir($coreCache, 0755, true);
            }

            // si $view tiene el caracter / usar $view como ruta completa - si no, usar $filename como nombre de archivo
            $filename = str_contains($view, '/') ? $view : $filename;

            // Inicializar BladeOne
            $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

            // Renderizar una vista
            $bladeView = $blade->run($filename, [
                'page_data' => [
                    ...$page_data,
                    ...$this->globalVariables->getGlobalVariables()
                ]
            ]);

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

    public function handle_view($executable = [], $route_config = [])
    {
        if (isset($route_config['metadata'])) {
            if (isset($route_config['metadata']['global']) && $route_config['metadata']['global'] === false) {
                $executable['metadata'] = [
                    'css' => [],
                    'js' => [],
                    'favicon' => ''
                ];
            } else {
                $executable = $this->metadata_handler->merge_metadata(array_merge($route_config, $executable));
            }
        } else {
            $global_metadata = $this->metadata_handler->get_global_metadata();
            if (is_array($executable)) {

                $executable['metadata'] = [
                    ...$global_metadata,
                    'css' => [],
                    'js' => [],
                    'favicon' => ''
                ];
            }
        }

        if (Env::get('DEV_MODE') === 'false' && array_key_exists('view', $executable)) {
            $this->cache_handler->write_cache_file($this->request->get_path(), $executable['view'] ?? null);
        }

        // $view = $this->view_handler->get_view($executable['view'] ?? null);

        $this->render($executable, $route_config, $executable['view'] ?? null);
    }
}
