<?php

namespace Core\Ui\Forms;

class FormBuilder
{
    public function __construct() {}

    public function build(array $form_fields)
    {
        $form_to_render = [];
        foreach ($form_fields as $field) {
            $__field = new $field['type'](
                $field['name'],
                $field['value'],
                $field['label'] ?? '',
                $field['placeholder'] ?? '',
                $field['error'] ?? ''
            );
            $form_to_render[] = $__field->render();
        }

        return $form_to_render;
    }
}
