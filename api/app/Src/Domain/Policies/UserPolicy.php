<?php

namespace App\Src\Domain\Policies;

use App\Models\User;
use App\Src\Domain\Services\Enums\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function isAdmin(User $requestUser)
    {
        return $requestUser->role === UserRole::ADMINISTRATOR;
    }
}
