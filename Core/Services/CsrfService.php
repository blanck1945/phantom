<?php

namespace Core\Services;

use Core\Response\PhantomResponse;

class CsrfService
{
    public function __construct() {}

    public function verify()
    {

        // Check if request is POST
        if (!isset($_POST['_token'])) {
            PhantomResponse::send(502, ['message' => 'CSRF Token is missing']);
        }

        // if ($_POST['_token'] !== $_SESSION['_token']) {
        //     PhantomResponse::send(502, ['message' => 'CSRF Token is invalid']);
        // }

        unset($_POST['_token']);

        return true;
    }
}
