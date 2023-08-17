<?php

namespace App\Src\Domain\Repositories\Contracts;

interface UserRepository
{
    public function logout($token);
    public function allRoles();
}
