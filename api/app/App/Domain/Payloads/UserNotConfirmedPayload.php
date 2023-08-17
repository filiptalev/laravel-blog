<?php

namespace App\App\Domain\Payloads;

class UserNotConfirmedPayload extends Payload
{
    protected $status = 409; //HTTP_CONFLICT

    public function getData()
    {
        return [
            'status' => "error",
            'type' => 'authentication_error',
            'code' => $this->status,
            'messages' => $this->data,
            'data' => []
        ];
    }
}
