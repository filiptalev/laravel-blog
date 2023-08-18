<?php

namespace App\Src\Actions\V1\Blog;

use App\Src\Responders\Blog\ShowBlogResponder;
use App\Src\Domain\Services\Blog\ShowBlogService;

class ShowBlogAction
{
    protected $service;
    protected $responder;

    public function __construct(ShowBlogService $service, ShowBlogResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke($id)
    {
        return $this->responder->withResponse($this->service->handle(['id' => $id]))->respond();
    }
}
