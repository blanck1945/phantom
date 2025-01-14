<?php

namespace Core\Ui\Forms\Fields;


class Password implements Field
{

    public function __construct(
        protected string $name,
        protected string $value,
        protected string $label = '',
        protected string $placeholder = '',
        private string $error = ''
    ) {}

    public function render()
    {
        $class = 'border rounded w-full px-4 py-2 ' . ($this->error ? 'border-red-500' : '');

        if (empty($this->label)) {
            return <<<HTML
            <div>
                 <input 
                    class="$class" 
                    value="{$this->value}" 
                    placeholder="{$this->placeholder}" 
                    type="password" 
                    name="{$this->name}"
                >
                <p class="text-red-500 font-semibold">{$this->error}</p>
            </div>
            HTML;
        } else {


            return <<<HTML
        <div>
            <label for="">{$this->label}</label>
            <input 
                class="border rounded w-full px-4 py-2 <?php echo $this->error ? 'border-red-500' : ''; ?>" 
                value="{$this->value}" 
                placeholder="{$this->placeholder}" 
                type="password" 
                name="{$this->name}"
            >
            <p>{$this->error}</p>
        </div>
        HTML;
        }
    }
}
