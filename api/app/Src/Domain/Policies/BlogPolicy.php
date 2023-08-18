<?php

namespace App\Src\Domain\Policies;

use App\Src\Domain\Models\User;
use App\Src\Domain\Models\Blog;
use App\Src\Domain\Services\Enums\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPolicy
{
    use HandlesAuthorization;

    public function show(User $requestUser, Blog $blog)
    {
        return $requestUser->role === UserRole::ADMINISTRATOR || $requestUser->id === $blog->user_id;
    }

    public function update(User $requestUser, Blog $blog)
    {
        return $requestUser->role === UserRole::ADMINISTRATOR || $requestUser->id === $blog->user_id;
    }

    public function delete(User $requestUser, Blog $blog)
    {
        return $requestUser->role === UserRole::ADMINISTRATOR || $requestUser->id === $blog->user_id;
    }
}
