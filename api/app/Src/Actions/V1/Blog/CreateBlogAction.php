<?php

namespace App\Src\Actions\V1\Blog;

use Illuminate\Http\Request;
use App\Src\Responders\Blog\CreateBlogResponder;
use App\Src\Domain\Services\Blog\CreateBlogService;

class CreateBlogAction
{
    protected $service;
    protected $responder;

    public function __construct(CreateBlogService $service, CreateBlogResponder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }

    public function __invoke(Request $request)
    {
        $blog = $this->service->handle(
            array_merge(
                $request->only(
                    [
                        'title',
                        'body',
                        'image',
                    ]
                ),
                [
                    'user_id' => auth()->user()->id
                ]
            )
        );

        return $this->responder->withResponse($blog)->respond();
    }
}
