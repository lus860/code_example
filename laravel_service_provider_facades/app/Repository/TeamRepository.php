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
     * @param $id
     * @param $pluckId
     * @return mixed
     */
    public function getTeamPluck($id, $pluckId)
    {
        return $this->startCondition()->whereIn('id', $id)->pluck($pluckId)->toArray();
    }

    /**
     * @param $id
     * @param $companyId
     * @return mixed
     */
    public function getTeamById($id, $companyId)
    {
        return $this->startCondition()->where(['id' => $id, 'id_company' => $companyId])->first();
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

