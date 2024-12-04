<?php

namespace Core\Ui\Forms\Fields;


class Select extends Field
{

    private $options;

    public function __construct(array $arguments)
    {

        parent::__construct($arguments);
        $this->options = $arguments['options'];
    }

    public function render()
    {
        return '<select name="' . $this->name . '">
        ' . $this->renderOptions() . '
        </select>';
    }

    public function renderOptions()
    {
        $options = '';

        foreach ($this->options as $label => $value) {
            $options .= '<option value="' . $value . '">' . $label . '</option>';
        }
        return $options;
    }
}
