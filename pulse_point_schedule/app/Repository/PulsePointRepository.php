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

    public function emotionSchedule($day, $companyId)
    {
        return $this->startCondition()
            ->whereDate('created_at', $day)
            ->where('id_company', $companyId)
            ->avg('feeling');
    }

    public function productivitySchedule($day, $companyId)
    {
        return $this->startCondition()
            ->whereDate('created_at', $day)
            ->where('id_company', $companyId)
            ->avg('productivity');
    }

}

