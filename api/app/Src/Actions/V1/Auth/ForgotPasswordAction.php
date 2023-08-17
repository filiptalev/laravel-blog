<?php

namespace App\Src\Actions\V1\Auth;

use Illuminate\Http\Request;
use App\Src\Responders\GenericResponder;
use App\Src\Domain\Services\Auth\ForgotPasswordService;

class ForgotPasswordAction
{
    protected $service;
    protected $responder;

    public function __construct(ForgotPasswordService $service, GenericResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request)
    {
        $data = $this->service->handle($request->only('email'));

        return $this->responder->withResponse($data)->respond();

    }
}
