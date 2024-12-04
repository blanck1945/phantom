<?php

namespace Core\Request;

class Request
{
    public function __construct(private array $params = [])
    {
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($key)
    {
        return $this->params[$key];
    }

    public function get_server()
    {
        return $_SERVER;
    }

    public function get_request()
    {
        return $_REQUEST;
    }

    public function get_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get_path()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getBody()
    {
        $body = [];
        $method = $this->get_method();

        if ($method === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($method === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
