<?php

namespace App\Repository;

use AppDateFormat;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
        $this->dateFormat = AppDateFormat::getDateFormat();

    }

    abstract protected function getModelClass();

    protected function startCondition()
    {
        return clone $this->model;
    }

    public function getModelPluck($query, $pluck)
    {
        return $query->pluck($pluck)->toArray();
    }
}
