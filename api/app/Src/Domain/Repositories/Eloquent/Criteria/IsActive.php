<?php

namespace App\Src\Domain\Repositories\Eloquent\Criteria;

use App\Src\Domain\Repositories\Criteria\CriterionInterface;


class IsActive implements CriterionInterface
{
    protected $isActive;

    public function __construct($isActive)
    {
        $this->isActive = $isActive;
    }
    public function apply($entity)
    {
        return $this->isActive ? $entity->whereIsActive($this->isActive) : $entity;
    }
}
