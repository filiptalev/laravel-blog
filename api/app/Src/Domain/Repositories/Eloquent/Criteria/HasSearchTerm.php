<?php

namespace App\Src\Domain\Repositories\Eloquent\Criteria;

use App\Src\Domain\Repositories\Criteria\CriterionInterface;

class HasSearchTerm implements CriterionInterface
{
    protected $searchTerm;
    protected $attributes;

    public function __construct($attributes, $searchTerm)
    {
        $this->searchTerm = $searchTerm;
        $this->attributes = $attributes;
    }
    public function apply($entity)
    {
        return $this->searchTerm ?  $entity->whereLike($this->attributes, $this->searchTerm) : $entity;
    }
}
