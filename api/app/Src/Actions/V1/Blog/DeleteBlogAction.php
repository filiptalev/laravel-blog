<?php

namespace App\Src\Actions\V1\Blog;

use App\Src\Responders\GenericResponder;
use App\Src\Domain\Services\Blog\DeleteBlogService;

class DeleteBlogAction
{
    protected $service;
    protected $responder;

    public function __construct(DeleteBlogService $service, GenericResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke($id)
    {
        $data = $this->service->handle(['id' => $id]);

        return $this->responder->withResponse($data)->respond();
    }
}
