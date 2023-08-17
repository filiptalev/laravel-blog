<?php

namespace App\Src\Domain\Services\User;

use App\App\Domain\ServiceInterface;
use App\App\Domain\Payloads\SuccessPayload;
use App\Src\Domain\Repositories\Eloquent\EloquentUserRepository;

class IndexUsersPaginatedService implements ServiceInterface
{
    protected $users;

    public function __construct(EloquentUserRepository $users)
    {
        $this->users = $users;
    }


    public function handle($data = [])
    {
        $orderField = isset($data['sortField']) && $data['sortField'] != '' ? $data['sortField'] : 'id';
        $orderType = isset($data['sortType']) && $data['sortType'] != '' ? $data['sortType'] : 'asc';
        $searchKeyword = isset($data['search']) && $data['search'] != '' ? $data['search'] : '';

        //TODO: do this logic in the resource
        return new SuccessPayload(
            $this->users->getUsersByRoleOrderedSearchedOrPaginated(
                $orderField,
                $orderType,
                $searchKeyword,
                $data['perPage'],
                $data['page']
            )
        );
    }
}
