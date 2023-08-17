<?php

namespace App\Src\Responders\User;

use App\App\Domain\Responders\Responder;
use App\Src\Domain\Resources\UserResource;
use App\App\Domain\Responders\ResponderInterface;

class UserResponder extends Responder implements ResponderInterface
{

    public function respond()
    {
        if ($this->hasErrorsPayload($this->response)) {
            return response()->json($this->response->getData(), $this->response->getStatus());
        }

        $data = $this->response->getData();
        $data['data'] = new UserResource($data['data']);

        return $data;
    }
}
