<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinPlan extends BaseModel
{
    protected $table = 'checkin_plans';

    protected $fillable = ['name', 'id_goal', 'id_checkins', 'created_by', 'created_at', 'updated_at'];

    public function checkin()
    {
        return $this->belongsTo('App\Models\Checkin', 'id_checkins');
    }

    public function goal()
    {
        return $this->belongsTo('App\Models\Goal', 'id_goal');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\CheckinPlanAttachment', 'id_checkin_plan');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file']);
    }

    public static function createCheckinPlan($request, $userId, $id_apm_checkins)
    {
        $apmCheckinPlan = new self();
        $apmCheckinPlan->name = $request->name;
        $apmCheckinPlan->id_checkins = $id_apm_checkins;
        $apmCheckinPlan->id_goal = $request->id_goal;
        $apmCheckinPlan->created_by = $userId;

        if ($apmCheckinPlan->save()) {
            return $apmCheckinPlan;
        }

        return false;
    }

    public static function updateCheckinPlan($request, $apmCheckinPlan, $id_apm_checkins)
    {
        $apmCheckinPlan->name = $request->name;
        $apmCheckinPlan->id_checkins = $id_apm_checkins;
        $apmCheckinPlan->id_goal = $request->id_goal;
        if ($apmCheckinPlan->save()) {
            return $apmCheckinPlan;
        }

        return false;
    }
}
