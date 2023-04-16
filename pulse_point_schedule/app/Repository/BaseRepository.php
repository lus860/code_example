<?php

namespace App\Repository;

use App\Models\IfStatement;
use Illuminate\Support\Facades\Auth;
use AppDateFormat;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    protected $model;

    abstract protected function getModelClass();

    protected function startCondition()
    {
        return clone $this->model;
    }

}
