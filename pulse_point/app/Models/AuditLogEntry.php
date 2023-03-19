<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogEntry extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_company', 'created_by', 'id_model_second', 'id_model_first', 'model_first', 'model_second', 'anonymous_user', 'page', 'action', 'private', 'ip', 'raw_data', 'completion_rate', 'id_user', 'link', 'created_at', 'updated_at'];

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id_company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function loggable()
    {
        return $this->morphTo(__FUNCTION__, 'model_first','id_model_first');
    }

    /**
     * @return mixed
     */
    public function loggableSecond()
    {
        return $this->morphTo(__FUNCTION__, 'model_second', 'id_model_second');
    }

    /**
     * @param $item
     * @param $action
     * @param $page
     * @param null $id_company
     * @param null $created_by
     * @param null $id_model_second
     * @param null $id_model_first
     * @param null $model_first
     * @param null $model_second
     * @param null $id_user
     * @param null $private
     * @param null $anonymous_user
     * @param null $completion_rate
     * @param int $link
     * @return mixed
     */
    public static function saveLog($item, $action, $page, $id_company = null, $created_by = null, $id_model_second = null, $id_model_first = null, $model_first = null, $model_second = null, $id_user = null, $private = null, $anonymous_user = null, $completion_rate = null, $link = 1)
    {
        $auditLogEntry = new self();

        $auditLogEntry->fill([
            "id_company" => $id_company ?? Auth::user()->id_company,
            "created_by" => $created_by ?? Auth::id(),
            "id_model_second" => $id_model_second ?? $item->id,
            "id_model_first" => $id_model_first ?? $item->id,
            "model_first" =>  $model_first ?? get_class($item),
            "model_second" =>  $model_second ?? get_class($item),
            "page" => $page ?? self::AUDIT_TRAIL,
            "id_user" => $id_user ?? null,
            "private" => $private ?? 0,
            "link" => $link,
            "anonymous_user" => $anonymous_user ?? 0,
            "completion_rate" => $completion_rate ?? null,
            "action" => $action,
            "ip" => $_SERVER['REMOTE_ADDR'],
        ]);
        return $auditLogEntry->save();
    }

}
