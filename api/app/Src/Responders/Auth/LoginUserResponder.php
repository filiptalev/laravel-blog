<?php

namespace App\Src\Responders\Auth;

use App\App\Domain\Responders\Responder;
use App\App\Domain\Responders\ResponderInterface;

class LoginUserResponder extends Responder implements ResponderInterface
{
    public function respond()
    {
        return response($this->response->getData())->setStatusCode($this->response->getStatus());
    }
}
