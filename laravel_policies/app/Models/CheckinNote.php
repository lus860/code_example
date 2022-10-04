<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinNote extends BaseModel
{
    protected $table = 'checkin_notes';

    protected $fillable = ['name', 'id_checkins', 'status', 'created_by', 'created_at', 'updated_at'];

    const PRIVATE = 1;
    const NOT_PRIVATE = 0;

    public function checkin()
    {
        return $this->belongsTo('App\Models\Checkin', 'id_checkins');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file']);
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\CheckinNoteAttachment', 'id_checkin_note');
    }

    public static function createCheckinNote($request, $userId, $id_apm_checkins)
    {
        $apmCheckinNote = new self();
        $apmCheckinNote->name = $request->name;
        $apmCheckinNote->id_checkins = $id_apm_checkins;
        $apmCheckinNote->created_by = $userId;
        if ($request->status) {
            $apmCheckinNote->status = self::PRIVATE;
        } else {
            $apmCheckinNote->status = self::NOT_PRIVATE;
        }
        if ($apmCheckinNote->save()) {
            return $apmCheckinNote;
        }

        return false;
    }

    public static function updateCheckinNote($request, $apmCheckinNote, $id_apm_checkins)
    {
        $apmCheckinNote->name = $request->name;
        $apmCheckinNote->id_checkins = $id_apm_checkins;
        if ($request->status) {
            $apmCheckinNote->status = self::PRIVATE;
        } else {
            $apmCheckinNote->status = self::NOT_PRIVATE;
        }
        if ($apmCheckinNote->save()) {

            return $apmCheckinNote;
        }

        return false;
    }
}
