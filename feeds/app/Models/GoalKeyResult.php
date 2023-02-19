<?php

namespace App\Models;

use App\Notifications\Goal\GoalKeyResultAssignedNotifications;
use App\Traits\CalculateStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class GoalKeyResult extends BaseModel
{
    use CalculateStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_goal', 'id_user', 'created_by', 'name', 'completion_rate', 'completion_rate_updated_at', 'deadline', 'deadline_at_risk_period', 'start_value', 'max_value', 'value', 'decimal', 'id_metric', 'created_at', 'updated_at'];

    protected $casts = [
        'deadline' => 'datetime',
        'completion_rate_updated_at' => 'datetime',
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function goal()
    {
        return $this->belongsTo('App\Models\Goal', 'id_goal');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    public function comments()
    {
        return $this->hasMany('App\Models\GoalKeyResultComment', 'id_goal_key_result');
    }

}
