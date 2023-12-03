<?php

namespace App\Repository;

use App\Models\User as Model;
use Illuminate\Support\Facades\Gate;

class UserRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    protected function startCondition()
    {
        return clone $this->model;
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function userTotalCount($id_company)
    {
        $query = $this->startCondition()
            ->where(['users.id_company' => $id_company]);

        $response = Gate::inspect('permission-by-role-list-users', [Model::class, $query]);

        if (!$response->allow()) {
            return false;
        }

        return $query->distinct('users.id')->count();
    }

}
