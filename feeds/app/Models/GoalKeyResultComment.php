<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalKeyResultComment extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_goal_key_result', 'created_by', 'message', 'id_parent', 'created_at', 'updated_at'];

    /**
     */
    public function key_result()
    {
        return $this->belongsTo('App\Models\GoalKeyResult', 'id_goal_key_result');
    }

    /**
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    /**
     */
    public function parent_comment()
    {
        return $this->belongsTo('App\Models\GoalKeyResultComment', 'id_parent');
    }

    public function child_comments()
    {
        return $this->hasMany('App\Models\GoalKeyResultComment', 'id_parent');
    }

}
