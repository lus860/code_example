<?php

namespace App\Repository;

use App\Models\Team as Model;

class TeamRepository extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function getTeams($companyId)
    {
        return $this->startCondition()->where('id_company', $companyId)->get();
    }
}

