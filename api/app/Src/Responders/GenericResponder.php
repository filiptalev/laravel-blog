<?php

namespace App\Src\Responders;

use App\App\Domain\Responders\Responder;
use App\App\Domain\Responders\ResponderInterface;

class GenericResponder extends Responder implements ResponderInterface
{
    public function respond()
    {
        return response()->json($this->response->getData(), $this->response->getStatus());
    }
}
