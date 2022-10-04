<?php

namespace App\Repository;

use App\Models\Checkin;
use App\Models\Checkin as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class CheckinRepository  extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCheckinById($id)
    {
        return $this->startCondition()->find($id);
    }

    public function getCheckins($request, $companyId)
    {
        $query = $this->startCondition()->where(['checkins.id_company' => $companyId]);
        $query->leftJoin('checkin_users', 'checkins.id', '=', 'checkin_users.id_checkins');
        $query->leftJoin('checkin_teams', 'checkins.id', '=', 'checkin_teams.id_checkins');

        $response = Gate::inspect('permission-by-role-for-checkin', [Model::class, $query]);

        if ($response->allowed()) {

            $filterForCheckins = $this->filterForChackins($request, $query);
            $query = $filterForCheckins['query'];

            $query->select(
                'checkins.id',
                'checkins.name',
                'checkins.description',
                'checkins.is_company',
                'checkins.date_time',
                'checkins.id_company',
                'checkins.created_by',
                'checkins.id_category',
                'checkins.created_at',
                'checkins.updated_at',
                'checkin_teams.id_team',
                'checkin_users.id_user'
            );

            $current_page = $request->query('page') ?? 1;
            $query->groupBy('checkins.id');
            $checkins = $query->paginate(env('PER_PAGE', 21), $columns = ['*'], $pageName = 'page', $page = $current_page);
            return $checkins->withPath('checkins');
        }

        return false;
    }

    public function getCheckinsForShowAction($companyId)
    {
        $query = $this->startCondition()->where(['checkins.id_company' => $companyId]);
        $query->leftJoin('checkin_users', 'checkins.id', '=', 'checkin_users.id_checkins');
        $query->leftJoin('checkin_teams', 'checkins.id', '=', 'checkin_teams.id_checkins');

        $response = Gate::inspect('permission-by-role-for-checkin', [Model::class, $query]);

        if ($response->allowed()) {

            $query->select(
                'checkins.id',
                'checkins.name',
                'checkins.description',
                'checkins.is_company',
                'checkins.date_time',
                'checkins.id_company',
                'checkins.created_by',
                'checkins.id_category',
                'checkins.created_at',
                'checkins.updated_at',
                'checkin_teams.id_team',
                'checkin_users.id_user'
            );

            return $query->groupBy('checkins.id');
        }

        return false;
    }

    public function checkinTotalCount($companyId)
    {
        $query = $this->startCondition()->where(['checkins.id_company' => $companyId]);
        $query->leftJoin('checkin_users', 'checkins.id', '=', 'checkin_users.id_checkins');
        $query->leftJoin('checkin_teams', 'checkins.id', '=', 'checkin_teams.id_checkins');

        $response = Gate::inspect('permission-by-role-for-checkin', [Model::class, $query]);

        if ($response->allowed()) {

            $query->select(
                'checkins.id',
                'checkins.name',
                'checkins.is_company',
                'checkins.date_time',
                'checkins.id_company',
                'checkins.created_by',
                'checkins.created_at',
                'checkins.updated_at'
            );

            return $query->distinct('checkins.id')->count();
        }

        return false;
    }

    public function filterForChackins($request, $query)
    {
        $sortBy = 'created_at';
        $sortByType = 'desc';

        if (!empty($request)) {
            if (!empty($request->input('user_name'))) {
                $user_name = $request->input('user_name');
            }

            if (!empty($request->input('checkin_name'))) {
                $checkin_name = $request->input('checkin_name');
            }

            if (!empty($request->input('category'))) {
                $idCategory = $request->input('category');
            }

            if (!empty($request->input('sort_by'))) {
                $sortBy = $request->input('sort_by');
            }

            if (!empty($request->input('sort_by_type')) && $request->input('sort_by_type') === 'asc') {
                $sortByType = $request->input('sort_by_type');
            }

            $qString = "";
            if (!empty($user_name)) {
                $nameArr = explode(',', $user_name);
                $bindItems = [];

                foreach ($nameArr as $k => $nameItem) {
                    $searchName = '%' . $nameItem . '%';
                    if ($k !== 0) {
                        $qString .= " OR ";
                    }
                    $qString .= "CONCAT_WS(' ', last_name, middle_name, first_name) LIKE ?
                        OR
                        CONCAT_WS(' ', last_name, first_name) LIKE ?
                        OR
                        CONCAT_WS(' ', first_name,  last_name) LIKE ?";
                    $bindItems[] = $searchName;
                    $bindItems[] = $searchName;
                    $bindItems[] = $searchName;
                }

                if ($qString !== "") {
                    $query->join('users', 'checkin_users.id_user', '=', 'users.id');
                    $query->where('checkins.is_company', Checkin::IS_COMPANY_NO);
                    $query->whereRaw("({$qString})", $bindItems);
                }
            }

            if (!empty($checkin_name)) {
                $query->where('name', 'like', '%'.$checkin_name.'%');
            }
        }

        if (!empty($sortBy)) {
            if (Schema::hasColumn(Model::getTableName(), $sortBy)) {
                $query->orderBy('checkins.' . $sortBy, $sortByType);
            }
        }

        return ['query' => $query, 'qString' => $qString];
    }

    public function getCheckinByIdAndByCompany($id, $companyId)
    {
        return $this->startCondition()->where(['id' => $id, 'id_company' => $companyId])->first();
    }

    public function getCheckinByIdWithOut($id_apm_checkins)
    {
        return $this->startCondition()->where('id', $id_apm_checkins)->first();
    }

    public function getCheckinByIdForShow($id, $user, $companyId)
    {
        $query = $this->startCondition()->where(['checkins.id_company' => $companyId, 'checkins.id' => $id]);

        $query->select(
            'checkins.id',
            'checkins.name',
            'checkins.is_company',
            'checkins.description',
            'checkins.date_time',
            'checkins.id_company',
            'checkins.id_category',
            'checkins.created_by',
            'checkins.created_at',
            'checkins.updated_at'
        );

        $query->with('checkin_teams')->with('checkin_users')->groupBy('checkins.id');
        return $query->first();
    }

    public function getCheckinByIdWith($id, $checkin_talking_point = true)
    {
        if ($checkin_talking_point) {
            return $this->startCondition()
                ->where('id', $id)
                ->with('checkin_users')
                ->with('checkin_teams')
                ->with('checkin_talking_point')
                ->first();
        } else {
            return $this->startCondition()
                ->where('id', $id)
                ->with('checkin_users')
                ->with('checkin_teams')
                ->first();
        }
    }

    public function getCheckinByIdForUpdate($id, $companyId)
    {
        return $this->startCondition()->where(['id_company' => $companyId, 'id' => $id])
            ->with(['checkin_users' => function ($query) {
                $query->addSelect('users.id', 'id_company', 'email', 'first_name');
            }])->with('checkin_teams')->with('checkin_talking_point')->first();
    }

    public function getCheckinForDashboardSection($company_id, $id_checkins, $addWeek)
    {
        return $this->startCondition()->where(['id_company' => $company_id])
            ->whereDate('date_time', '<=', $addWeek)
            ->whereDate('date_time', '>=', Carbon::now())
            ->whereIn('id', $id_checkins)->get();
    }

    public function getCheckinCount($company_id, $id_checkins, $end, $start)
    {
        return $this->startCondition()->where(['id_company' => $company_id])
            ->whereDate('date_time', '<=', $end)
            ->whereDate('date_time', '>=', $start)
            ->whereIn('id', $id_checkins)->count();
    }

    public function getCheckinIds($companyId)
    {
        return $this->startCondition()->where('id_company', $companyId)
            ->pluck('id')->toArray();
    }

}
