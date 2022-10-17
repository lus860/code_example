<?php

namespace App\Repository;

use App\Models\CheckinPlan as Model;

class CheckinPlanRepository  extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCheckinPlanById($id)
    {
        return $this->startCondition()->where('id', $id)->first();
    }

    public function getCheckinPlanPaginate($id_apm_checkins)
    {
        return $this->startCondition()
            ->where('id_checkins', $id_apm_checkins)
            ->orderBy('created_at', 'desc')
            ->paginate(env('PER_PAGE_MIN'));
    }
}
