<?php

namespace App\Classes;

class ResponseBuilder
{
    public function resSuccess(array $response)
    {
        return [
            'metadata' => [
                'date' => date('Y-m-d'),
            ],
            'data' => $response,
        ];
    }

    public function resError($message, $statusCode, $errorCode)
    {
        return [
            'status_code' => $statusCode,
            'error_code' => $errorCode,
            'message' => $message,
        ];
    }
}