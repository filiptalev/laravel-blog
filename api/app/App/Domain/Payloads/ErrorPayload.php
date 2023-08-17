<?php

namespace App\App\Domain\Payloads;

class ErrorPayload extends Payload
{
    protected $status = 400;

    public function getData()
    {
        return [
            'status' => "error",
            'type' => 'general_error',
            'code' => $this->status,
            'message' => $this->data,
            'data' => []
        ];
    }
}
