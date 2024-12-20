<?php

namespace Core\Ui\Forms\Fields;


class Password implements Field
{

    public function __construct(protected string $name, protected string $label, protected string $value)
    {
    }

    public function render()
    {
        return <<<HTML
        <div>
            <label for="">{$this->label}</label>
            <input value="{$this->value}" type="password" name="{$this->name}">
        </div>
        HTML;
    }
}
