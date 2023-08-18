<?php

namespace App\Src\Domain\Repositories\Contracts;

interface BlogRepository
{
    public function getSearchByAttributes();

    public function getBlogsOrderedSearchedOrPaginatedForAdmin(
        $orderField,
        $orderType,
        $searchKeyword,
        $perPage,
        $page
    );

    public function getBlogsOrderedSearchedOrPaginatedActiveForUser(
        $orderField,
        $orderType,
        $searchKeyword,
        $perPage,
        $page,
        $userId
    );
}
