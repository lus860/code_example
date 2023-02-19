<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Mail\WeeklyStatus\WeeklyPlanStatusChangeMail;
use App\Mail\WeeklyStatus\WeeklyPlanRemindAddMondayMail;
use App\Mail\WeeklyStatus\WeeklyPlanSummarySundayMail;
use App\Mail\WeeklyStatus\WeeklyPlanCreatedOrUpdatedMail;
use App\Mail\WeeklyStatus\WeeklyPlanAttachedMail;
use Illuminate\Support\Facades\Log;

class WeeklyTask extends BaseModel
{
    const ACCOMPLISHMENT = 'accomplishment';
    const PLAN = 'plan';
    const CHALLENGE = 'challenge';

    const CHALLENGE_STATUS = 'challenges';
    const PLAN_STATUS = 'plans';
    const ACCOMPLISHMENT_STATUS = 'accomplishments';
    const COMING_UP_STATUS = 'coming-up';
    const WEEK = 'Week';
    const MONTH = 'Month';
    const QUARTER = 'Quarter';
    const YEAR = 'Year';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'id_user',
        'name',
        'id_goal',
        'created_by',
        'deadline',
        'completion_date',
        'challenge_date',
        'status',
        'created_status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'completion_date' => 'datetime',
        'challenge_date' => 'datetime',
    ];

    public function feeds()
    {
        return $this->hasMany('App\Models\Feed', 'id_weekly_task');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\WeeklyTaskComment', 'id_weekly_tasks')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    public function goal()
    {
        return $this->belongsTo('App\Models\Goal', 'id_goal');
    }

}
