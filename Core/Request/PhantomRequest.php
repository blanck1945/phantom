<?php

namespace Core\Request;

use Core\Dto\Dto;

class PhantomRequest
{
    private array $queryParams = [];
    private array $body = [];

    private string $method;
    private string $path;
    private Dto $bodyDto;
    private array $request;

    public function __construct(private array $params = [])
    {
        $this->body = $_POST;
        $this->method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
        $this->request = $_REQUEST;
    }

    public function getParams()
    {
        return $this->queryParams;
    }

    public function setParam(string $key, mixed $value)
    {
        $this->queryParams[$key] = $value;
    }

    public function getParam($key, $convertIfPosible = true)
    {
        $param = $this->queryParams[$key];

        if ($convertIfPosible) {
            if (is_numeric($param)) {
                return (int) $param;
            }
        }

        return $param;
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

    public function getDtoProps()
    {
        return get_object_vars($this->bodyDto);
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setBodyProperty($key, $value)
    {
        $this->body[$key] = $value;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function __get($name)
    {
        return $this->params[$name];
    }

    public function getUser()
    {
        if (isset($_REQUEST['user'])) {
            return $_REQUEST['user'];
        }

        return null;
    }
}
