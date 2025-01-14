<?php

namespace Core\Services;

use Core\Interfaces\IValidator;
use Core\Response\PhantomResponse;
use Core\Services\FormService\FormService;
use Core\Ui\Forms\FormBuilder;

class ValidatorService
{
    private IValidator $validator;

    public function __construct(
        $validator,
    ) {
        $this->validator = new $validator(new FormService(new FormBuilder()));
    }

    public function validate(array $body): array
    {
        $errors = [];

        foreach ($this->validator->validations as $key => $validation) {
            foreach ($validation as $pipe) {
                $pipe_class = $pipe;

                if (is_array($pipe)) {
                    $pipe_class = $pipe[0];
                }

                $exec = new $pipe_class($key, $body[$key] ?? '');

                if (is_array($pipe[1])) {
                    $result = $exec->handler($body[$key], $key, ...$pipe[1] ?? []);
                } else {
                    $result = $exec->handler($body[$key], $key);
                }

                if ($result) {
                    $errors[$key] = $result[$key];
                }
            }
        }

        if ($this->validator->extrict_validation ?? false && count($errors) > 0) {
            PhantomResponse::send(400, $errors);
        }

        if (count($errors) > 0) {
            return $this->validator->error_case($body, $errors);
        }

        return $errors;
    }

    public function handle_errors($body, $errors)
    {
        return $this->validator->error_case($body, $errors);
    }
}
