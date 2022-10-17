<?php

namespace App\Repository;

use App\Models\User;
use App\Models\User as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class UserRepository  extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $id
     * @param $companyId
     * @return mixed
     */
    public function getUserById($id, $companyId)
    {
        return $this->startCondition()->where(['id' => $id, 'id_company' => $companyId])->first();
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function getUsers($companyId)
    {
        return $this->startCondition()->where('id_company', $companyId)->get();
    }

    /**
     * @param $ids
     * @param $pluckId
     * @return mixed
     */
    public function getUserToArray($ids, $pluckId)
    {
        return $this->startCondition()->whereIn('id', $ids)->pluck($pluckId)->toArray();
    }

    /**
     * @param $companyId
     * @param array $columns
     * @return mixed
     */
    public function getUserForCompany($companyId, $columns = [])
    {
        if (count($columns) == 0) {
            return $this->startCondition()->where(['id_company' => $companyId, 'active' => Model::ACTIVE])->get();
        }
        return $this->startCondition()->select($columns)->where(['id_company' => $companyId, 'active' => Model::ACTIVE])->get();
    }

    /**
     * @param $ids
     * @param null $with
     * @return mixed
     */
    public function getUsersByIds($ids, $with = null)
    {
        if ($with) {
            return $this->startCondition()->where('active', Model::ACTIVE)->whereIn('id', $ids)->with($with)->get();
        }
        return $this->startCondition()->where('active', Model::ACTIVE)->whereIn('id', $ids)->get();
    }

    /**
     * @param $ids
     * @param $with
     * @return mixed
     */
    public function getUsersByIdsColumns($ids, $with)
    {
        return $this->startCondition()->where('active', Model::ACTIVE)->whereIn('id', $ids)->with($with)->select('id', 'first_name', 'active', 'id_apm_acl_role', 'id_company', 'deleted_at')->get();
    }

    /**
     * @param $ids
     * @param $companyId
     * @return mixed
     */
    public function getUsersByIdsForCompany($ids, $companyId)
    {
        return $this->startCondition()->where(['id_company' => $companyId, 'active' => Model::ACTIVE])->whereIn('id', $ids)->get();
    }

    public function getAdmins()
    {
        return $this->startCondition()->where(['active' => Model::ACTIVE, 'id_apm_acl_role' => Model::ACL_ADMIN])->get();
    }

    /**
     * @param $id_company
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($id_company, $email)
    {
        return $this->startCondition()->where(['active' => Model::ACTIVE, 'id_company' => $id_company, 'email' => $email])->first();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmailWithCompany($email)
    {
        return $this->startCondition()->where(['active' => Model::ACTIVE, 'email' => $email])->with('company')->first();
    }

    public function userTotalCount($id_company)
    {
        $query = $this->startCondition()
            ->where(['users.id_company' => $id_company]);

        $response = Gate::inspect('permission-by-role-list-users', [Model::class, $query]);

        if (!$response->allow()) {
            return false;
        }

        return $query->distinct('users.id')->count();
    }

    /**
     * @param $id_company
     * @param $thisUser
     * @param null $is_mobile
     * @param null $req
     * @param false $forAllUsers
     * @return false
     */
    public function getUserForTeam($id_company, $is_mobile = null)
    {
        $query = $this->startCondition()->select('users.*',
            DB::raw('AVG( weekly_ratings.rating ) as rating'))
            ->where(['users.id_company' => $id_company, 'users.active' => Model::ACTIVE])
            ->with('custom_field_answer');

        $response = Gate::inspect('permission-by-role-list-users', [Model::class, $query]);

        if (!$response->allow()) {
            return false;
        }

        $query->groupBy('users.id');

        $paginate = env('PER_PAGE', 21);
        if (isset($is_mobile) && $is_mobile) {
            $paginate = env('PER_PAGE_MOBILE', 20);
        }
        return $query->paginate($paginate);

    }
}
