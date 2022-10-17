<?php

namespace App\Repository;

use App\Models\CheckinTalkingPoint as Model;

class CheckinTalkingPointRepository  extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCheckinTalkingPointById($id)
    {
        return $this->startCondition()->where('id', $id)->first();
    }

    public function getCheckinTalkingPointByIdCheckin($id_apm_checkins)
    {
        return $this->startCondition()->where('id_checkins', $id_apm_checkins)->get();
    }

    public function getCheckinTalkingPointIds($id_apm_checkins)
    {
        return $this->startCondition()->where('id_checkins', $id_apm_checkins)
            ->where('status', Model::DONE)->pluck('id')->toArray();
    }

    public function getCheckinTalkingPointPaginate($id_apm_checkins)
    {
        return $this->startCondition()
            ->where('id_checkins', $id_apm_checkins)
            ->orderBy('created_at', 'desc')
            ->paginate(env('PER_PAGE_MIN'));
    }

    public function getCheckinTalkingPointCount($array)
    {
        return $this->startCondition()
            ->where($array)
            ->count();
    }

}
