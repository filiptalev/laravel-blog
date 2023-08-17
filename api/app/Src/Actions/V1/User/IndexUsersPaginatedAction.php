<?php

namespace App\Src\Actions\V1\User;

use Illuminate\Http\Request;
use App\Src\Responders\User\UsersCollectionResponder;
use App\Src\Domain\Services\User\IndexUsersPaginatedService;

class IndexUsersPaginatedAction
{
    protected $service;
    protected $responder;

    public function __construct(IndexUsersPaginatedService $service, UsersCollectionResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request)
    {
        $data = $this->service->handle(
            $request->only(['columnFilters', 'page', 'perPage', 'sortField', 'sortType', 'search'])
        );

        return $this->responder->withResponse($data)->respond();
    }
}
