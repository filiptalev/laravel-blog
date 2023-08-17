<?php

namespace App\App\Domain\Payloads;

class UnauthenticatedPayload extends Payload
{
    protected $status = 401;

    public function getData()
    {
        return [
            'status' => "error",
            'type' => 'authentication_error',
            'code' => $this->status,
            'message' => $this->data,
            'data' => []
        ];
    }
}
