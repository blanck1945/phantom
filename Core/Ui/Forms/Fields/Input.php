<?php

namespace Core\Ui\Forms\Fields;


class Input implements Field
{

    public function __construct(
        protected string $name,
        protected string $label,
        protected string $value,
        protected string $placeholder = ''
    ) {}

    public function render()
    {
        return <<<HTML
        <div>
            <label for="">{$this->label}</label>
            <input value="{$this->value}" placeholder="{$this->placeholder}" type="text" name="{$this->name}">
        </div>
        HTML;
    }
}
