<?php

namespace App\Src\Actions\V1\User;

use Illuminate\Http\Request;
use App\Src\Responders\User\UserResponder;
use App\Src\Domain\Services\User\ShowUserService;

class ShowUserAction
{
    protected $service;
    protected $responder;

    public function __construct(ShowUserService $service, UserResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, $id)
    {
        $data = $this->service->handle([
            'id' => $id
        ]);

        return $this->responder->withResponse($data)->respond();
    }
}
