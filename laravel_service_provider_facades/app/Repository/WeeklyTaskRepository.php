<?php

namespace App\Repository;

use App\Models\User;
use App\Models\WeeklyTask as Model;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AccountController;

class WeeklyTaskRepository extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $start
     * @param $status
     * @param $companyId
     * @param $userId
     * @return mixed
     */
    // get weekly items for user by status for that week

    public function getWeeklyItem($start, $status, $companyId, $userId)
    {
        $end = Carbon::parse($start)->endOfWeek(Carbon::FRIDAY)->format($this->dateFormat);

        if ($status == Model::PLAN) {
            return $this->startCondition()->withCount('attachments')->withCount('comments')
                ->where(['id_company' => $companyId, 'status' => $status, 'id_user' => $userId])
                ->where('deadline', '>=', $start)
                ->where('deadline', '<=', $end)
                ->orderBy('created_at', 'desc')->paginate(env('PER_PAGE_MIN'));
        } else if ($status == Model::ACCOMPLISHMENT) {
            return $this->startCondition()->withCount('attachments')->withCount('comments')
                ->where(['id_company' => $companyId, 'status' => $status, 'id_user' => $userId])
                ->where('completion_date', '>=', $start)
                ->where('completion_date', '<=', $end)
                ->orderBy('created_at', 'desc')->paginate(env('PER_PAGE_MIN'));
        }

        return $this->startCondition()->withCount('attachments')->withCount('comments')
            ->where(['id_company' => $companyId, 'status' => $status, 'id_user' => $userId])
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at', 'desc')->paginate(env('PER_PAGE_MIN'));
    }

    /**
     * @param $start
     * @param $companyId
     * @param $userId
     * @return mixed
     */
    // get weekly items for user by status 'coming_up'

    public function getComingUp($start, $companyId, $userId)
    {
        $end = Carbon::parse($start)->endOfWeek(Carbon::FRIDAY)->format($this->dateFormat);
        $endNextFriday = Carbon::parse($end)->addWeeks()->format($this->dateFormat);
        $startNextMonday = Carbon::parse($start)->addWeeks()->format($this->dateFormat);
        return $this->startCondition()->select('id', 'id_company', 'name', 'id_goal', 'deadline', 'completion_date',  DB::raw('"comingUp" as status'), 'created_at', 'updated_at')
            ->withCount('attachments')->withCount('comments')
            ->where(['id_company' => $companyId, 'status' => Model::PLAN, 'id_user' => $userId])
            ->where('deadline', '>=', $startNextMonday)
            ->where('deadline', '<=', $endNextFriday)
            ->orderBy('created_at', 'desc')->paginate(env('PER_PAGE_MIN'));
    }

}
