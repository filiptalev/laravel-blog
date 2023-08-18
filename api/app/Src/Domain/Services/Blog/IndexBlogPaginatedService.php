<?php

namespace App\Src\Domain\Services\Blog;

use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\SuccessPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentBlogRepository;


class IndexBlogPaginatedService implements ServiceInterface
{
    protected $blogs;

    public function __construct(EloquentBlogRepository $blogs)
    {
        $this->blogs = $blogs;
    }

    public function handle($data = [])
    {
        $orderField = isset($data['sortField']) && $data['sortField'] != '' ? $data['sortField'] : 'id';
        $orderType = isset($data['sortType']) && $data['sortType'] != '' ? $data['sortType'] : 'asc';
        $searchKeyword = isset($data['search']) && $data['search'] != '' ? $data['search'] : '';

        if(auth()->user()->role == 'administrator') {
            return new SuccessPayload(
                $this->blogs->getBlogsOrderedSearchedOrPaginatedForAdmin(
                    $orderField,
                    $orderType,
                    $searchKeyword,
                    $data['perPage'],
                    $data['page']
                )
            );
        }
        //TODO: do this logic in the resource
        return new SuccessPayload(
            $this->blogs->getBlogsOrderedSearchedOrPaginatedActiveForUser(
                $orderField,
                $orderType,
                $searchKeyword,
                $data['perPage'],
                $data['page'],
                auth()->user()->id
            )
        );
    }
}
