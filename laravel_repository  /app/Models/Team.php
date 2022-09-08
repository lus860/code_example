<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Team extends BaseModel
{
    protected $fillable = ['name', 'color', 'id_company', 'created_by', 'created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'team_users', 'id_team', 'id_user')->where('active', User::ACTIVE)->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file']);
    }

    public static function createTeam($request, $userId, $companyId)
    {
        $team = new self();
        $team->name = $request->name;
        $team->color = $request->color;
        $team->id_company = $companyId;
        $team->created_by = $userId;
        if ($team->save()) {
            return $team;
        }
        return false;
    }

    public static function updateTeam($team, $request, $companyId)
    {
        $team->name = $request->name;
        $team->color = $request->color;
        $team->id_company = $companyId;
        if ($team->save()) {
            return $team;
        }
       return false;
    }
}
