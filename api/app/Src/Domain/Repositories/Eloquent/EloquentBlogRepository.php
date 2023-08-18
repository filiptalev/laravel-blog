<?php
namespace App\Src\Domain\Repositories\Eloquent;

use App\Src\Domain\Models\Blog;
use App\Src\Domain\Repositories\RepositoryAbstract;
use App\Src\Domain\Repositories\Contracts\BlogRepository;

class EloquentBlogRepository extends RepositoryAbstract implements BlogRepository
{
    public function entity()
    {
        return Blog::class;
    }

    public function getSearchByAttributes()
    {
        return  $this->entity::$searchByAttributes;
    }

    public function getBlogsOrderedSearchedOrPaginatedForAdmin(
        $orderField,
        $orderType,
        $searchKeyword,
        $perPage,
        $page
    ) {
        return $this->entity->where(function ($q) use ($searchKeyword) {
                $q->where('title', 'like', '%' . $searchKeyword . '%');
            })
            ->orderBy($orderField, $orderType)->paginate($perPage, ['*'], 'page', $page);
    }

    public function getBlogsOrderedSearchedOrPaginatedActiveForUser(
        $orderField,
        $orderType,
        $searchKeyword,
        $perPage,
        $page,
        $userId
    ) {
        return $this->entity->where(function ($q) use ($searchKeyword) {
                $q->where('title', 'like', '%' . $searchKeyword . '%');
            })->where('user_id', $userId)
            ->orderBy($orderField, $orderType)->paginate($perPage, ['*'], 'page', $page);
    }
}
