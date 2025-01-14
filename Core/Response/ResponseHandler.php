<?php

namespace Core\Response;

class ResponseHandler
{

    public function successResponse(array $data)
    {
        return [
            'status' => 'success',
            'statusCode' => 200,
            ...$data
        ];
    }
}
