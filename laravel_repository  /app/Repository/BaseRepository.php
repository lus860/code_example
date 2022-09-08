<?php

namespace App\Repository;

abstract class BaseRepository
{
// If the project is large, then in this case it is correct to use the
// repository to avoid initialization of models and controllers.
// The repository is used to retrieve data from the database and
// the models are used to store and update the data in the database.

    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
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
