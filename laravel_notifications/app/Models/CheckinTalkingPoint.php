<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinTalkingPoint extends BaseModel
{
    const DONE = 1;
    const NOT_DONE = 0;

    protected $table = 'checkin_talking_points';

    protected $fillable = ['name', 'id_checkins', 'status', 'created_by', 'created_at', 'updated_at'];

    public function checkin()
    {
        return $this->belongsTo('App\Models\Checkin', 'id_checkins');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file']);
    }

    public static function createCheckinTalkingPoint($request, $userId, $id_apm_checkins)
    {
        $apmCheckinTalkingPoint = new self();
        $apmCheckinTalkingPoint->name = $request->name;
        $apmCheckinTalkingPoint->id_checkins = $id_apm_checkins;
        $apmCheckinTalkingPoint->created_by = $userId;

        if ($apmCheckinTalkingPoint->save()) {
            return $apmCheckinTalkingPoint;
        }
        return false;
    }

    public static function updateCheckinTalkingPoint($id, $request)
    {
        $apmCheckinTalkingPoint = self::where('id', $id)->update(['name' => $request->name]);
        if ($apmCheckinTalkingPoint) {
            return $apmCheckinTalkingPoint;
        }

        return false;
    }
}
