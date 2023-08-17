<?php

namespace App\Src\Actions\V1\User;

use Illuminate\Http\Request;
use App\Src\Responders\User\UserResponder;
use App\Src\Domain\Services\User\UpdateUserService;

class UpdateUserAction
{
    protected $service;
    protected $responder;

    public function __construct(UpdateUserService $service, UserResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, $id)
    {
        $data = $this->service->handle(
            array_merge(
                $request->only([
                    'first_name',
                    'last_name',
                    'email',
                    'current_password',
                    'password',
                    'password_confirmation',
                ]),
                ['id' => $id]
            )
        );

        return $this->responder->withResponse($data)->respond();
    }
}
