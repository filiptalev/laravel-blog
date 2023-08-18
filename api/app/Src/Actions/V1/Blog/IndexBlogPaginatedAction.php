<?php
namespace App\Src\Actions\V1\Blog;

use Illuminate\Http\Request;
use App\Src\Responders\Blog\IndexBlogPaginatedResponder;
use App\Src\Domain\Services\Blog\IndexBlogPaginatedService;

class IndexBlogPaginatedAction
{

    protected $service;
    protected $responder;

    public function __construct(IndexBlogPaginatedService $service, IndexBlogPaginatedResponder $responder)
    {
        $this->service= $service;
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

