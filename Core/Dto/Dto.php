<?php

namespace Core\Dto;

use Core\Response\PhantomResponse;

class Dto
{

    /**
     * Array of validations
     * 
     * @var array
     */
    private array $validations = [];

    /**
     * Hanlde extrict validation  - Default: false
     * 
     * if true, the request will stop and return an error if any validation fails
     * 
     * if false, the request will continue and return all errors
     * 
     * @var bool
     */
    private bool $extrict_validation = true;


    public function __construct(private array $route_validations)
    {
        $this->validations = $route_validations;
    }

    /**
     * Apply validation to the request body
     * 
     * @param string $prop {property to apply validation - Example: 'username'}
     * @param array $pipes {array of pipes - Example: [IsEmpty, IsEmail]}
     * @return array {array of errors}
     */
    public function apply_validation(): array
    {
        $errors = [];

        foreach ($this->validations as $key => $validation) {
            foreach ($validation as $pipe) {
                $pipe_class = $pipe;

                if (is_array($pipe)) {
                    $pipe_class = $pipe[0];
                }

                $exec = new $pipe_class($key, $this->$key);

                if (is_array($pipe[1])) {
                    $result = $exec->handler($this->$key, $key, ...$pipe[1] ?? []);
                } else {
                    $result = $exec->handler($this->$key, $key);
                }

                if ($result) {
                    $errors[] = $result;
                }
            }
        }

        if ($this->extrict_validation && count($errors) > 0) {
            PhantomResponse::send(400, $errors);
        }

        return $errors;
    }
}
