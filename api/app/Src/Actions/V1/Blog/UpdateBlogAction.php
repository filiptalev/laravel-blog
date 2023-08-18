<?php

namespace App\Src\Actions\V1\Blog;

use Illuminate\Http\Request;
use App\Src\Responders\Blog\UpdateBlogResponder;
use App\Src\Domain\Services\Blog\UpdateBlogService;

class UpdateBlogAction
{
    protected $service;
    protected $responder;

    public function __construct(UpdateBlogService $service, UpdateBlogResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, $id)
    {
        $blog = $this->service->handle(
            array_merge($request->only(
                [
                    'title',
                    'body',
                    'image',
                ]
            ), ['id' => $id])
        );
        return $this->responder->withResponse($blog)->respond();
    }
}
