<?php

namespace App\Src\Domain\Services\Blog;

use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\SuccessPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentBlogRepository;


class IndexBlogService implements ServiceInterface
{
    protected $blogs;

    public function __construct(EloquentBlogRepository $blogs)
    {
        $this->blogs = $blogs;
    }

    public function handle($data = [])
    {
        return new SuccessPayload(
            $this->blogs->all()
        );
    }
}
