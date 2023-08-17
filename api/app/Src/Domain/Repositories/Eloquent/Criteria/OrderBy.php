<?php

namespace App\Src\Domain\Repositories\Eloquent\Criteria;

use App\Src\Domain\Repositories\Criteria\CriterionInterface;

class OrderBy implements CriterionInterface
{
    protected $orderByHow;

    public function __construct($orderByHow)
    {
        $this->orderByHow = $orderByHow;
    }
    public function apply($entity)
    {
        if (!$this->orderByHow) {
            return $entity;
        }

        $orderBy = explode(',', $this->orderByHow)[0];
        $orderHow = explode(',', $this->orderByHow)[1];

        return $entity->orderBy($orderBy, $orderHow);
    }
}
