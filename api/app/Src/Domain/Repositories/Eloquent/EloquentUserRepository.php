<?php

namespace App\Src\Domain\Repositories\Eloquent;

use App\Src\Domain\Models\User;
use App\Src\Domain\Services\Enums\UserRole;
use App\Src\Domain\Repositories\RepositoryAbstract;
use App\Src\Domain\Repositories\Contracts\UserRepository;

class EloquentUserRepository extends RepositoryAbstract implements UserRepository
{
    public function entity()
    {
        return User::class;
    }
    public function logout($token)
    {
        return $this->entity->logout($token);
    }

    public function allRoles()
    {
        return UserRole::getConstants();
    }

    public function newPasswordByResetToken($token, $password)
    {
        return  $this->entity->newPasswordByResetToken($token, $password);
    }

    public function getMe($id, $role)
    {
        return $this->entity->getMe($id, $role);
    }


    public function findByRole($role)
    {
        return $this->entity->where('role', $role)->get();
    }

    public function getUsersByRoleOrderedSearchedOrPaginated(
        $orderField,
        $orderType,
        $searchKeyword,
        $perPage,
        $page
    ) {
        return $this->entity->where(function ($q) use ($searchKeyword) {
            $q->where('first_name', 'like', '%' . $searchKeyword . '%')
                ->orWhere('last_name', 'like', '%' . $searchKeyword . '%')
                ->orWhere('email', 'like', '%' . $searchKeyword . '%')
                ->orWhere('role', 'like', '%' . $searchKeyword . '%');
        })
            ->orderBy($orderField, $orderType)->paginate($perPage, ['*'], 'page', $page);
    }
}
