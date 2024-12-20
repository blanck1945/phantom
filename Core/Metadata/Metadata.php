<?php

namespace Core\Metadata;

class Metadata
{

    public function __construct(private array $config) {}

    public function get_metadata()
    {
        return $this->config;
    }

    public function add_metadata($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function get_global_metadata()
    {
        return $this->get_metadata();
    }

    public function merge_metadata($page_data)
    {
        $core_metada = $this->get_metadata();

        if (is_array($page_data['metadata']) && array_key_exists('js', $page_data['metadata'])) {
            $new_js_metadata = [];
            foreach ($page_data['metadata']['js'] as $key => $value) {
                $new_js_metadata[] = $value . '.js';
            }
            $page_data['metadata']['js'] = $new_js_metadata;
        }

        if (is_array($page_data['metadata']) && array_key_exists('css', $page_data['metadata'])) {
            $new_js_metadata = [];
            foreach ($page_data['metadata']['css'] as $key => $value) {
                $new_js_metadata[] = $value . '.css';
            }
            $page_data['metadata']['css'] = $new_js_metadata;
        }

        $page_data['metadata']['css'] = array_merge($core_metada['css'] ?? [], $page_data['metadata']['css'] ?? []);
        $page_data['metadata']['js'] = array_merge($core_metada['js'] ?? [], $page_data['metadata']['js'] ?? []);
        $page_data['metadata']['favicon'] = $core_metada['favicon'];

        return $page_data;
    }
}
