<?php

namespace App\Src\Domain\Repositories\Criteria;

interface CriterionInterface
{
    public function apply($entity);
}
