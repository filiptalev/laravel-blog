<?php

namespace App\Src\Actions\V1\Auth;

use Illuminate\Http\Request;
use App\Src\Responders\GenericResponder;
use App\Src\Domain\Services\Enums\UserRole;
use App\Src\Domain\Services\Auth\RegisterUserService;

class RegisterUserAction
{
    protected $service;
    protected $responder;

    public function __construct(RegisterUserService $service, GenericResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request)
    {
        $defaults = [
            'role' => UserRole::USER,
        ];

        $data = $this->service->handle(
            array_merge(
                $request->only([
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                    'password_confirmation',
                ]),
                $defaults
            )
        );

        return $this->responder->withResponse($data)->respond();
    }
}
