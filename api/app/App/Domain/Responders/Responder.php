<?php

namespace App\App\Domain\Responders;

use App\App\Domain\Payloads\ErrorPayload;
use App\App\Domain\Payloads\ValidationPayload;
use App\App\Domain\Payloads\UnauthorizedPayload;

abstract class Responder
{
    protected $response;

    public function withResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public function hasErrorPayload($response)
    {
        if (
            $response instanceof ValidationPayload ||
            $response instanceof ErrorPayload ||
            $response instanceof UnauthorizedPayload
        ) {
            return true;
        }

        return false;
    }
}
