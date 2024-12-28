<?php

namespace Core\Request;

class PhantomRequest
{
    private array $queryParams = [];
    private array $body = [];

    private string $method;
    private string $path;
    private $bodyDto = null;

    public function __construct(private array $params = [])
    {
        $this->queryParams = $_GET;
        $this->body = $_POST;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
    }

    public function getParams()
    {
        return $this->queryParams;
    }

    public function setParam(string $key, mixed $value)
    {
        $this->queryParams[$key] = $value;
    }

    public function getParam($key)
    {
        return $this->queryParams[$key];
    }

    public function get_server()
    {
        return $_SERVER;
    }

    public function get_method()
    {
        return $this->method;
    }

    public function get_path()
    {
        return $this->path;
    }

    public function setDto($dto)
    {
        $this->bodyDto = $dto;
    }

    public function getDto()
    {
        return $this->bodyDto;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setBodyProperty($key, $value)
    {
        $this->body[$key] = $value;
    }

    public function getBody()
    {
        return $this->body;
        // $body = [];
        // $method = $this->get_method();

        // if ($method === 'GET') {
        //     foreach ($_GET as $key => $value) {
        //         $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        //     }
        // }

        // if ($method === 'POST') {
        //     foreach ($_POST as $key => $value) {
        //         $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        //     }
        // }

        // return $body;
    }

    public function __get($name)
    {
        return $this->params[$name];
    }
}
