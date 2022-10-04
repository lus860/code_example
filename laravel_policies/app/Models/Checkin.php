<?php

namespace App\Models;

use App\Mail\CheckIn\CheckInUserAddedMail;
use App\Mail\CheckIn\CheckInStartedMail;
use App\Mail\CheckIn\CheckInAttachedMail;
use App\Mail\CheckIn\CheckInInProgressMail;
use Illuminate\Support\Facades\Log;

class Checkin extends BaseModel
{
    protected $fillable = ['name', 'description', 'created_by', 'id_category', 'date_time', 'id_company', 'is_company', 'status', 'is_past', 'created_at', 'updated_at'];

    const PAST = 1;
    const NOT_PAST = 0;
    const IN_PROGRESS = 1;
    const NOT_IN_PROGRESS = 0;

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id_company');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file']);
    }

    public function checkin_users()
    {
        return $this->belongsToMany('App\Models\User', 'checkin_users', 'id_checkins', 'id_user');
    }

    public function checkin_teams()
    {
        return $this->belongsToMany('App\Models\Team', 'checkin_teams', 'id_checkins', 'id_team');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'checkin_users', 'id_checkins', 'id_user');
    }

    public function checkin_talking_point()
    {
        return $this->hasMany('App\Models\CheckinTalkingPoint', 'id_checkins','id');
    }

    public function checkin_plans()
    {
        return $this->hasMany('App\Models\CheckinPlan', 'id_checkins','id');
    }

    public function checkin_notes()
    {
        return $this->hasMany('App\Models\CheckinNote', 'id_checkins','id');
    }

    public static function createCheckIn($request, $userId, $companyId)
    {
        $checkin = new self();
        $checkin->name = $request->name;
        $checkin->id_company = $companyId;
        $checkin->created_by = $userId;
        $checkin->id_category = $request->id_category;
        $checkin->date_time = $request->date_time;
        $checkin->description = $request->description;
        if (isset($request->company)) {
            $checkin->is_company = $request->company;
        }

        if ($checkin->save()) {
            return $checkin;
        }

        return false;
    }

}
