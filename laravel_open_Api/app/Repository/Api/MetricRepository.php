<?php

namespace App\Repository\Api;

use App\Models\Metric as Model;

class MetricRepository extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getMetricPluck($id, $pluckId)
    {
        return $this->startCondition()->whereIn('id', $id)->pluck($pluckId)->toArray();
    }

    public function getMetricById($id, $companyId)
    {
        return $this->startCondition()->where(['id' => $id, 'id_company' => $companyId])->first();
    }

    public function getMetricByName($name, $companyId)
    {
        return $this->startCondition()->where(['name' => $name, 'id_company' => $companyId])->first();
    }

    public function getMetrics($companyId, $sortByType)
    {
        $sortBy = 'created_at';

        $query = $this->startCondition()->where(['id_company' => $companyId])->orWhere(['id_company' => null]);

        return $query->orderBy($sortBy, $sortByType)->get();
    }

}

