<?php

namespace App\App\Domain\Payloads;

class UnauthorizedPayload extends Payload
{
    protected $status = 401;

    public function getData()
    {
        return [
            'status' => "error",
            'type' => 'authorization_error',
            'code' => $this->status,
            'message' => $this->data,
            'data' => []
        ];
    }
}
