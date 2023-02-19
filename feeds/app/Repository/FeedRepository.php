<?php

namespace App\Repository;

use App\Models\Feed as Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FeedRepository extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getFeedPluck($id, $pluckId)
    {
        return $this->startCondition()->whereIn('id', $id)->pluck($pluckId)->toArray();
    }

    public function getFeedById($id, $companyId)
    {
        return $this->startCondition()->where(['id' => $id, 'id_company' => $companyId])->first();
    }

    public function getFeedByUserId($id_user, $companyId)
    {
        return $this->startCondition()->where(['id_user' => $id_user, 'id_company' => $companyId])->with('goal')->with('goal.comments')->with('key_result')->with('key_result.comments')->with('weekly_status')->with('weekly_status.comments')->orderBy('feeds.updated_at', 'desc')->paginate(env('PER_PAGE'));
    }

    public function getFeedByUserIdForPage($id_user, $companyId)
    {
        return $this->startCondition()->where(['id_user' => $id_user, 'id_company' => $companyId])->orderBy('feeds.updated_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getFeedByUserIdByWeeklyStatusId($id_user, $companyId, $weeklyItemId)
    {
        return $this->startCondition()->where(['id_user' => $id_user, 'id_company' => $companyId, 'id_weekly_task' => $weeklyItemId])->first();
    }

    public function getFeedByUserIdByKeyResultId($id_user, $companyId, $keyResultId)
    {
        return $this->startCondition()->where(['id_user' => $id_user, 'id_company' => $companyId, 'id_goal_key_result' => $keyResultId])->first();
    }

    public function getFeedByUserIdByGoalId($id_user, $companyId, $goalId)
    {
        return $this->startCondition()->where(['id_user' => $id_user, 'id_company' => $companyId, 'id_goal' => $goalId])->first();
    }

    public function getFeedByUserIdCount($id_user, $companyId)
    {
        return $this->startCondition()->where(['id_user' => $id_user, 'id_company' => $companyId])->whereNull('read_at')->count();
    }

}
