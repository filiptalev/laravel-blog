<?php

namespace App\Src\Actions\V1\Auth;

use Illuminate\Http\Request;
use App\Src\Responders\GenericResponder;
use App\Src\Domain\Services\Auth\RecoverPasswordService;

class RecoverPasswordAction
{
    protected $service;
    protected $responder;

    public function __construct(RecoverPasswordService $service, GenericResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, $token)
    {
        $data = $this->service->handle(['password' => $request->get('password'), 'token' => $token]);

        return $this->responder->withResponse($data)->respond();
    }
}
