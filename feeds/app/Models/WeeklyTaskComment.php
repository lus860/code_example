<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WeeklyTaskComment extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_weekly_tasks', 'created_by', 'message', 'created_at', 'updated_at'];

    /**
     */
    public function task()
    {
        return $this->belongsTo('App\Models\WeeklyTask', 'id_weekly_tasks');
    }

    /**
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

}
