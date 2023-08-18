<?php

namespace App\Src\Actions\V1\Blog;

use Illuminate\Http\Request;
use App\Src\Responders\Blog\IndexBlogResponder;
use App\Src\Domain\Services\Blog\IndexBlogService;

class IndexBlogAction
{

    protected $service;
    protected $responder;

    public function __construct(IndexBlogService $service, IndexBlogResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request)
    {
        $data = $this->service->handle();

        return $this->responder->withResponse($data)->respond();
    }
}
