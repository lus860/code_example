<?php

namespace App\Models;

use App\Http\Controllers\AccountController;
use App\Traits\CalculateStatus;
use Carbon\Carbon;
use App\Mail\Goal\GoalAssignedMail;
use App\Mail\Goal\GoalUnassignedMail;
use App\Mail\Goal\GoalDeadlineChangedMail;
use App\Mail\Goal\GoalKeyResultsCreatedOrDeadlineChangedMail;
use App\Mail\Goal\GoalDeadlineCheckingMail;
use App\Mail\Goal\GoalOffTrackMail;
use App\Mail\Goal\GoalAttachedMail;
use Illuminate\Support\Facades\Log;

class Goal extends BaseModel
{
    use CalculateStatus;

    const PRIVATE = 1;
    const NOT_PRIVATE = 0;

    /**GoalWeeklyItemsLinked
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_company', 'created_by', 'name', 'id_goal_type', 'deadline', 'deadline_at_risk_period', 'completion_rate', 'completion_rate_updated_at', 'is_active', 'is_private', 'description', 'id_parent', 'created_at', 'updated_at'];

    protected $casts = [
        'deadline' => 'datetime',
        'completion_rate_updated_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id_company');
    }

    public function weeklyTask()
    {
        return $this->hasMany('App\Models\WeeklyTask', 'id_goal');
    }

    public function goal_users()
    {
        return $this->belongsToMany('App\Models\User', 'goal_users', 'id_goal', 'id_user');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\GoalComment', 'id_goal');
    }

    /**
     */
    public function key_results()
    {
        return $this->hasMany('App\Models\GoalKeyResult', 'id_goal');
    }

    /**
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

}
