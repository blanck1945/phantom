<?php

namespace config;

use Core\Request\PhantomRequest;

class GlobalVariables
{
    public function __construct(private readonly PhantomRequest $phantomRequest) {}

    /**
     * Global variables are available in all views 
     * 
     * Add your global variables here
     * 
     * @return array
     */
    public function getGlobalVariables(): array
    {
        return [
            'appVersion' => '1.0.0',
            'user' => $this->phantomRequest->getUser() ?? null,
        ];
    }
}
