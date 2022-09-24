<?php

namespace App\Repository;

use App\Models\User as Model;

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

}
