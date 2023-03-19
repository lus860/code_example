<?php

namespace App\Repository;

use App\Models\PulsePoint as Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PulsePointRepository extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $userId
     * @param $companyId
     * @param $created_at
     * @return mixed
     */
    public function getPulsePointByUserIdForToday($userId, $companyId, $created_at)
    {
        return $this->startCondition()->where(['id_user' => $userId, 'id_company' => $companyId])->whereDate('created_at', $created_at)->first();

    }

}

